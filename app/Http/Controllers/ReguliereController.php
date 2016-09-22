<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Guard;
use Illuminate\Http\Request;
use App\Reguliere;
use App\Exceptionnelle;
use App\Enfant;
use App\Library\Feries;


class ReguliereController extends Controller {


	public function __construct()
	{
		$this->middleware('parents');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index( Guard $auth )
	{
		$id = $auth->user()->id;
		$enfants = $auth->user()->enfants()->with('classe')->get();
		return view('calendrier.index', compact('enfants'));
	}

	public function testInscription(Request $request)
	{
		if(!$request->ajax()){
			abort(404);
		}

		$data = $request->all();
		$dates = $data['date'];

		if ( date('N',strtotime($dates)) >= 6){
			return response()->json(['response' => 'week_end']);
		}

		if ( Feries::est_ferie($dates) == true ){
			return response()->json(['response' => 'ferie']);
		}

		$xmlurl = 'http://telechargement.index-education.com/vacances.xml';
		$xml = simplexml_load_file($xmlurl);
		$calendrier = $xml->calendrier;
		$json = json_encode($calendrier->zone[2]);
		$tab = json_decode($json,true);

		foreach ( $tab['vacances'] as $vac ){
			$debut = date('Y-m-d',strtotime($vac['@attributes']['debut']));
			$fin = date('Y-m-d',strtotime($vac['@attributes']['fin']));
			if ( $dates > $debut && $dates < $fin){
				
				return response()->json(['response' => 'vacances']);
			}
		}

		$type = $data['type'];
		$id = $data['id'];
		$exep = Exceptionnelle::where('enfant_id', $id)->where('type',$type)->where('jour', $dates)->get()->count();
		if($exep == 1){
			return response()->json(['response' => 'fail']);
		}
		$reg = Reguliere::where('enfant_id', $id)->where('type',$type)->first();
		if($reg != null && $reg->count() != 0){
			$jours = str_split($reg->jours);
			$day = date('w',strtotime($dates));
			if(in_array($day,$jours)){
				return response()->json(['ok' =>'Desinscription']);
			}
		}
		return response()->json(['ok' => 'Inscription']);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($idE, Guard $auth)
	{
		$user = $auth->user();
		$enfant = Enfant::findOrFail($idE);
		if($user->enfants()->where('enfants.id', $idE)->count() == 0 && $user->niveau == 0){
			return redirect(route('reguliere.index'));
		}
		$reg = Reguliere::where('enfant_id', $idE)->with('enfant')->get();
		if($reg->count() == 0){
			$id = $auth->user()->id;
			$user =  $auth->user();
			for ($i=0; $i < 3; $i++) { 
				$inscriptions[$i] = new Reguliere;
				$inscriptions[$i]->jours = '';
				$inscriptions[$i]->enfant_id = $idE;
			}
			$inscriptions[0]->type = "cantine";
			$inscriptions[1]->type = "bus_matin";
			$inscriptions[2]->type = "bus_soir";
			for ($i=0; $i < 3; $i++) { 
				$inscriptions[$i]->save();
			}
			$reg = Reguliere::where('enfant_id', $idE)->get();
		}
		for ($i=0; $i < 3; $i++) { 
			$jours[$reg[$i]->type] = str_split($reg[$i]->jours);
		}
		$message = "";
		return view('calendrier.editReg', compact('reg', 'idE', 'jours','message','enfant'));
	}

	public function update($idE, Request $request, Guard $auth)
	{
		$enfant = Enfant::findOrFail($idE);
		$user = $auth->user();
		if($user->enfants()->where('enfants.id', $idE)->count() == 0 && $user->niveau == 0){
			return redirect(route('regulier.index'));
		}
		$data = $request->all();
		$niveau = $user->niveau;
		$reg = Reguliere::where('enfant_id', $idE)->get();
		$cantine = $reg[0];
		$bus_matin = $reg[1];
		$bus_soir = $reg[2];
		$cantine->modificate_by = $niveau;
		$bus_matin->modificate_by = $niveau;
		$bus_soir->modificate_by = $niveau;
		if(isset($data['cantine'])){
			$cantine->jours = implode($data['cantine']);
		}else{
			$cantine->jours = "";
		}
		$cantine->update();
		if(isset($data['bus_matin'])){
			$bus_matin->jours = implode($data['bus_matin']);
		}else{
			$bus_matin->jours = "";
		}
		$bus_matin->update();
		if(isset($data['bus_soir'])){
			$bus_soir->jours = implode($data['bus_soir']);
		}else{
			$bus_soir->jours = "";
		}
		$bus_soir->update();
		$reg[0]= $cantine;
		$reg[1]= $bus_matin;
		$reg[2]= $bus_soir;
		for ($i=0; $i < 3; $i++) { 
			$jours[$reg[$i]->type] = str_split($reg[$i]->jours);
		}
		$message = "Inscriptions réussies !";
		return view('calendrier.editReg', compact('reg', 'idE', 'jours','message','enfant'));
	}

}
