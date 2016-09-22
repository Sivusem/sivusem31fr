<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Enfant;
use App\Classe;
use Illuminate\Http\Request;
use App\Http\Requests\EnfantsRequest;
use App\Reguliere;
use App\Exceptionnelle;
use App\Arret;
use Carbon\Carbon;
use Input;
use App\Library\Feries;

class AffichageController extends Controller {

	
	public function __construct()
	{
		$this->middleware('adsem');
	}
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function index()
	{

	}

	public function cantine()
	{
		$tab = Feries::chargement(); // MODIF 1
		if(date('n') <= 6){
			$annee = date('Y', strtotime('-1 year', strtotime(date('Y'))));
		}else{
			$annee = date('Y');
		}
		$rentree = Feries::jour_rentree($annee,$tab);
		$jour_debut = date('N', strtotime($rentree));
		$diff = -($jour_debut)+1;
		$jourListe = date('Y-m-d',strtotime($diff.'days',strtotime($rentree)));
		$semaines= [];
		$tmp = date('Y-m-d',strtotime('+21days',strtotime(date('Y-m-d') )));
		while ( $jourListe < $tmp ){
			$day = date('N',strtotime($jourListe));
			$diff = 5-$day;
			$debutSemaine = $jourListe;
			$finSemaine = date('Y-m-d',strtotime('+'.$diff.'days',strtotime($debutSemaine)));
			$semaines[] =[ 'debut' =>$debutSemaine , 'fin' => $finSemaine ];
			$jourListe = date('Y-m-d',strtotime('+3 days',strtotime($finSemaine)));
		}
		$jourSelect = Input::get('semaine');
		if($jourSelect == null){
			$dernier_element = end($semaines);
			$dernier_element = prev($semaines);
			$jourSelect = date("Y-m-d",strtotime($dernier_element['debut']));
		}
		$jour = Carbon::now();
		$debutSemaine = $jourSelect;
		$finSemaine = new Carbon($debutSemaine);
		$day = date('N',strtotime($finSemaine));
		$diff = 5-$day;
		$finSemaine->addDay($diff);


			$regs = $this->selectionnerInscriptionsRegulieresCantine ();
			$exeps = $this->selectionnerInscriptionsExceptionnellesCantine ($debutSemaine, $finSemaine);

		$ferie = [];
		
		for ($i=0; $i < 5 ; $i++) { 
			$monjour = date('Y-m-d', strtotime($i.'day'.$debutSemaine));
			if(Feries::est_ferie($monjour) || Feries::est_vacances($monjour,$tab)){ // MODIF 2
				$ferie[] = $i+1;
			}
		}
		foreach($regs as $reg){
			$inscrits[$reg->enfant_id]['inscription'] = str_split($reg->jours);
			$inscrits[$reg->enfant_id]['enfant'] = $reg;
			if(!empty($ferie)){
				foreach ($ferie as $f) {
					$cle = array_search($f, $inscrits[$reg->enfant_id]['inscription']);
					if($cle !== null){
						unset($inscrits[$reg->enfant_id]['inscription'][$cle]);
					}
				}
			}
		}
		foreach($exeps as $exep){
//			if($exep->inscrit === 1){ // fred : 16/11/15 report correction fonction journalier à tout hasard
 			if($exep->inscrit === '1'){
				$inscrits[$exep->enfant_id]['inscription'][] = date( 'w' , strtotime($exep->jour));
				if(!isset($inscrits[$exep->enfant_id]['enfant'])){
					$inscrits[$exep->enfant_id]['enfant'] = $exep;
				}
			}else{
				$cle = array_search(date('w',strtotime($exep->jour)), $inscrits[$exep->enfant_id]['inscription']);
				if($cle !== false){
					unset($inscrits[$exep->enfant_id]['inscription'][$cle]);
				}
			}
		}
		if(!empty($inscrits)){
			return view('affichage.cantine',compact('inscrits','semaines','jourSelect'));
		}
		else{
			$message="Pas d'inscription en cours.";
			return view('affichage.cantine',compact('message','semaines','jourSelect'));
		}
	}
	
	/**
	 * @param debutSem
	 * @param finSem
	 */private function selectionnerInscriptionsExceptionnellesCantine($debutSem, $finSem) {
		$exeps = Exceptionnelle::with(['enfant'=>function ($query)
												{$query->where('mange_cantine',true)->where('niveau_classe','<>','EX');},
									'enfant.classe'])
		->whereBetween('jour', [$debutSem, $finSem])
		->where('type', 'cantine')
		->get();
		return $exeps;
	}

	/**
	 * 
	 */private function selectionnerInscriptionsRegulieresCantine() {

	// il faudrait trier par niveau
		$regs = Reguliere::with(['enfant'=>function ($query)
												{$query->where('mange_cantine',true)->where('niveau_classe','<>','EX');},
								'enfant.classe'])
			->where('type', 'cantine')
			->get();
	return $regs;
	}


	public function journalier($type)
	{
		$jour = Input::get('jour');
		if($jour == null){
			$jour = date("Y-m-d");
		}
		$inscrits = [];
		if ( $type == 'garderie'){
			return $this->affichageGarderie ($type, $jour);
		}
		// Ajout Fred : 16/11/2015 : type 'cantine' pas pris en compte
		elseif ($type == 'cantine') {
		return $this->affichageCantine ($type, $jour);

				
		}
		else{
			return $this->affichageBus ($type, $jour);

		}
	}
	/**
	 * @param type
	 * @param jour
	 */private function affichageBus($type, $jour) {
		$regs = Reguliere::with('enfant', 'enfant.classe','enfant.arret')
			->where('jours', 'LIKE', '%'.date('w', strtotime($jour)).'%')
			->where('type', $type)
			->get();
			
		$exeps = Exceptionnelle::with('enfant', 'enfant.classe','enfant.arret')
			->where('jour', date('Y-m-d', strtotime($jour)))
			->where('type', $type)
			->get();
			
		foreach($regs as $reg){
			$inscrits[$reg->enfant_id] = $reg;
		}
		foreach ($exeps as $exep) {
//				if($exep->inscrit === 1){    // Fred : 16/11/15 ne passe pas le test si === 1
			if($exep->inscrit === '1'){
				$inscrits[$exep->enfant_id] = $exep;
			}
			else{
				unset($inscrits[$exep->enfant_id]);
			}
		}

$tab = Feries::chargement();
if (!empty($inscrits)){
			if ( Feries::est_vacances(date('Y-m-d',strtotime($jour)),$tab) ){ // Fred : pourquoi est_vacances et pas est_ferié aussi?
				$message = "Pas d'inscription pendant les vacances.";
				return view('affichage.autres',compact('inscrits','message','jour','type'));
			}
			 else{ // ajout Fred 16/11/15
			$arrets = Arret::get();
			return view('affichage.autres',compact('inscrits','jour','type','arrets'));
}
} // ajout Fred 16/11/15
else{
		$message="Pas d'inscription en cours.";
		return view('affichage.autres',compact('inscrits','message','jour','type'));
}
	}

	/**
	 * @param type
	 * @param jour
	 */private function affichageCantine($type, $jour) {
		// Fred : code pas garanti : copier/coller aproximatifs	
			$regs = Reguliere::with(['Enfant', 'Enfant.Classe'])
			->where('jours', 'LIKE', '%'.date('w', strtotime($jour)).'%')
			->where('type', $type)
			->get();
			
			$exeps = Exceptionnelle::with(['Enfant', 'Enfant.Classe'])
			->where('jour', date('Y-m-d', strtotime($jour)))
			->where('type', $type)
			->get();
			
			foreach($regs as $reg){
				$inscrits[$reg->enfant_id] = $reg;
			}
			foreach ($exeps as $exep) {
				if($exep->inscrit === '1'){
					$inscrits[$exep->enfant_id] = $exep;
				}
				else{
					unset($inscrits[$exep->enfant_id]);
				}
			}
			
			return $this->affichageInscrits ( $inscrits, $jour, $type );

	}
	
	/**
	 * @param type
	 * @param jour
	 */private function affichageGarderie($type, $jour) {
		$inscrits = Enfant::where('garderie',true)
						->where('niveau_classe','<>','EX')
						->orderBy('classe_id')->get();
		return $this->affichageInscrits ( $inscrits, $jour, $type );
	}
	
	/**
	 * @param inscrits
	 * @param jour
	 * @param type
	 */private function affichageInscrits($inscrits, $jour, $type ) {
	 $tab = Feries::chargement();
	 if (!empty($inscrits)){
	 	if ( Feries::est_vacances(date('Y-m-d',strtotime($jour)),$tab) ){ // Fred : pourquoi est_vacances et pas est_ferié aussi?
	 		$message = "Pas d'inscription pendant les vacances.";
	 		return view('affichage.autres',compact('inscrits','message','jour','type'));
	 	}
	 	else{
	 		return view('affichage.autres',compact('inscrits','jour','type'));
	 	}
	 }
	 else{
	 	$message="Pas d'inscription en cours.";
	 	return view('affichage.autres',compact('inscrits','message','jour','type'));
	 }
	}
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(EnfantsRequest $request)
	{
		
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
	
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, EnfantsRequest $request)
	{
		
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		
	}

}
