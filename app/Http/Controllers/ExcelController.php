<?php namespace App\Http\Controllers;

// use Illuminate\Auth\Guard;
use App;
// use App\Http\Requests;
use Illuminate\Http\Request;
use App\Enfant;
use App\Reguliere;
use App\Exceptionnelle;
use Maatwebsite\Excel\Facades\Excel;
use App\Library\Feries;
use App\Facturation;
// use App\Ligne;
// use DB;

class ExcelController extends Controller {


	public function __construct()
	{
		$this->middleware('adsem');
	}

	public function index(){
		return view('commande.index');
	}

	public function calcul_mois(){
		$tab = Feries::chargement();
		if(date('n') <= 6){
			$annee = date('Y', strtotime('-1 year', strtotime(date('Y'))));
		}else{
			$annee = date('Y');
		}
		$rentree = Feries::jour_rentree($annee,$tab);
		$rentree = date('Y-m-d',strtotime('-'.(date('N',strtotime($rentree))-1).'day',strtotime($rentree)));	
		
		$mois = [];
		while ( $rentree < date('Y-m-d', strtotime('+2 months', strtotime(date('Y-m-d'))))){
			
			$fin = Feries::deux_mois($rentree);
			$mois[] = [ "debut" => $rentree , "fin" => $fin ];
			
			$rentree = $fin;
		}
		$mois_selectionne = end($mois);
		
		return view('commande.facturation',compact('mois','mois_selectionne'));
	}

	public function commande($dates)
	{
		ini_set('max_execution_time', 500);
		if(date('n') <= 6){
			$annee = date('Y', strtotime('-1 year', strtotime(date('Y'))));
		}else{
			$annee = date('Y');
		}
		$tabVacances = Feries::chargement();
		//ajouter une request ! ou gestion des dates
		//$dates = "2015-09-1";
		$dates = date('Y-m-d', strtotime($dates));
		$semaine = [$dates, date('Y-m-d',strtotime(date('Y-m-d', strtotime($dates))."+4 day"))];
		for($i=1; $i<=5; $i++){
			$jour = date('Y-m-d',strtotime(date('Y-m-d', strtotime($dates))."+".($i-1)." day"));
			//dd(Feries::est_vacances($jour, $tabVacances), $jour);
			$data[$i]['primaire']['total'] = (!Feries::est_ferie($jour) && !Feries::est_vacances($jour, $tabVacances))? 1 : 0;
			$data[$i]['primaire']['fixe'] = (!Feries::est_ferie($jour) && !Feries::est_vacances($jour, $tabVacances))? 1 : 0;
			$data[$i]['primaire']['exceptionnelle'] = 0;
			$data[$i]['primaire']['porc'] = 0;
			$data[$i]['primaire']['viande'] = 0;
			$data[$i]['maternelle']['total'] = (!Feries::est_ferie($jour) && !Feries::est_vacances($jour, $tabVacances))? 1 : 0;
			$data[$i]['maternelle']['fixe'] = (!Feries::est_ferie($jour) && !Feries::est_vacances($jour, $tabVacances))? 1 : 0;
			$data[$i]['maternelle']['exceptionnelle'] = 0;
			$data[$i]['maternelle']['porc'] = 0;
			$data[$i]['maternelle']['viande'] = 0;
			$data[$i]['adulte']['total'] = 0;
			$data[$i]['adulte']['instituteur'] = 0;
			$data[$i]['adulte']['personnelle'] = 0;
			$data[$i]['adulte']['fixe'] = 0;
			$data[$i]['adulte']['exceptionnelle'] = 0;
			$data[$i]['adulte']['porc'] = 0;
			$data[$i]['adulte']['viande'] = 0;
		}
		$data[3]['maternelle']['total'] = 0;
		$data[3]['maternelle']['fixe'] = 0;
		$maternelle_mercredi['total'] = 0;
		$maternelle_mercredi['porc'] = 0;
		$maternelle_mercredi['viande'] = 0;
		$facturation = [];
		$regs = Reguliere::with('enfant', 'enfant.classe')->where('type','cantine')->get();
		foreach ($regs as $reg) {
			//dd($reg->enfant);
			if($reg->enfant->exception_autre != 'non' && $reg->enfant->exception_autre != ''){
				$data['eviction'][strtolower($reg->enfant->classe->cycle)][$reg->enfant->id]['enfant'] = $reg->enfant;
				$data['eviction'][strtolower($reg->enfant->classe->cycle)][$reg->enfant->id]['jour'] = str_split($reg->jours);
			}
			$jours = str_split($reg->jours);
			for($i=1; $i<=5; $i++){
				//ajouter test vacance et jour ferie
				$jour = date('Y-m-d',strtotime(date('Y-m-d', strtotime($dates))."+".($i-1)." day"));
				if(!Feries::est_ferie($jour) && !Feries::est_vacances($jour, $tabVacances)){
					if(in_array($i, $jours)){
						$cycle = strtolower($reg->enfant->classe->cycle);
						$data[$i][$cycle]['fixe']++;
						if(isset($facturation[$reg->enfant_id]['fixe'])){
							$facturation[$reg->enfant_id]['fixe']++;
						}else{
							$facturation[$reg->enfant_id]['fixe'] = 1;
						}
						/*if($reg->enfant->classe->cycle == 'adulte'){
							$data[$i]['adulte'][$reg->enfant->classe->niveau] ++;
						}*/
						if($i == 3 && $cycle == 'maternelle'){
							/*$maternelle_mercredi['total']++;
							if($reg->enfant->exception_porc){
								$maternelle_mercredi['porc']++;
							}
							if($reg->enfant->exception_viande){
								$maternelle_mercredi['viande']++;
							}*/
							$cycle = 'primaire';
						}
						$data[$i][$cycle]['total']++;
						if($reg->enfant->exception_porc){
							$data[$i][$cycle]['porc']++;
						}
						if($reg->enfant->exception_viande){
							$data[$i][$cycle]['viande']++;
						}
					}
				}
			}			
		}
		$exeps = Exceptionnelle::whereBetween('jour', $semaine)->with('enfant', 'enfant.classe')->where('type','cantine')->get();
		//dd($exeps);
		foreach ($exeps as $exep) {
			$jour = date('N', strtotime($exep->jour));
			if(!Feries::est_ferie($exep->jour) && !Feries::est_vacances($exep->jour, $tabVacances)){
				$cycle = strtolower($exep->enfant->classe->cycle);
				//if($exep->inscrit == 0){// Fred : 13/12/15 correction de bug propagée, mettre entre ' les comparaisons d'inscrits
				if($exep->inscrit === '0'){
					$ajout = -1;

					$facturation[$exep->enfant_id]['fixe']--;
					/*if($jour != 3 || $cycle != 'maternelle'){
						$data[$jour][$cycle]['total']--;
					}*/
					if(isset($facturation[$exep->enfant_id]['abscence_signalee'])){
						$facturation[$exep->enfant_id]['abscence_signalee']++;
					}else{
						$facturation[$exep->enfant_id]['abscence_signalee'] = 1;
					}
				}else{
					$ajout = 1;
					$data[$jour][$cycle]['exceptionnelle']++;
					if(isset($facturation[$exep->enfant_id]['exceptionnelle'])){
						$facturation[$exep->enfant_id]['exceptionnelle']++;
					}else{
						$facturation[$exep->enfant_id]['exceptionnelle'] = 1;
					}
				}
				$data[$jour][$cycle]['fixe']+=$ajout;
				if($exep->enfant->exception_autre != "non" && $exep->enfant->exception_autre != ""){
					if($ajout == 1){
						$data['eviction'][$cycle][$exep->enfant->id]['jour'][] = date('N', strtotime($exep->jour));

						if(!isset($data['eviction'][$cycle][$exep->enfant->id]['enfant'])){
							$data['eviction'][$cycle][$exep->enfant->id]['enfant'] = $exep->enfant;
						}
					}else{
						//dd($data['eviction'][$cycle]);
						$cle = array_search(date('N', strtotime($exep->jour)), $data['eviction'][$cycle][$exep->enfant->id]['jour']);
						if($cle !== null){
							unset($data['eviction'][$cycle][$exep->enfant->id]['jour'][$cle]);
							//dd($data['eviction'][$cycle][$exep->enfant->id]['jour']);
						}
						if(empty($data['eviction'][$cycle][$exep->enfant->id]['jour'])){
							unset($data['eviction'][$cycle][$exep->enfant->id]);
						}
					}
				}
				if($jour == 3 && $cycle == 'maternelle'){
					/*$maternelle_mercredi['total']+=$ajout;
					if($exep->enfant->exception_porc){
						$maternelle_mercredi['porc']+=$ajout;
					}
					if($exep->enfant->exception_viande){
						$maternelle_mercredi['viande']+=$ajout;
					}*/
					$cycle = 'primaire';
				}
				if($exep->enfant->classe->cycle == 'adulte'){
					$data[$jour]['adulte'][$exep->enfant->classe->niveau] += $ajout;
				}
				$data[$jour][$cycle]['total']+=$ajout;
				if($exep->enfant->exception_porc){
					$data[$jour][$cycle]['porc']+=$ajout;
				}
				if($exep->enfant->exception_viande){
					$data[$jour][$cycle]['viande']+=$ajout;
				}
			}
		}
		$ancienne = Facturation::where('semaine', $dates)->delete();
		foreach($facturation as $id => $f){
			$fact = new Facturation;
			$fact->semaine = $dates;
			$fact->enfant_id = $id;
			$fact->fixe = (isset($f['fixe']))? $f['fixe'] : 0;
			$fact->non_fixe = (isset($f['exceptionnelle']))? $f['exceptionnelle'] : 0;
			$fact->abscence_signalee = (isset($f['abscence_signalee']))? $f['abscence_signalee'] : 0;
			$fact->save();
		}
		//Les adultes mangent des repas enfant ...
		for($i = 1; $i <=5 ; $i++){
			$data[$i]['primaire']['total'] += $data[$i]['adulte']['total'];
			$data[$i]['primaire']['fixe'] += $data[$i]['adulte']['fixe'];
			$data[$i]['primaire']['exceptionnelle'] += $data[$i]['adulte']['exceptionnelle'];
			$data[$i]['primaire']['porc'] += $data[$i]['adulte']['porc'];
			$data[$i]['primaire']['viande'] += $data[$i]['adulte']['viande'];
		}
		//dd($data);
		Excel::load('fichiers/test.xlsx', function($file)use($data, $maternelle_mercredi, $semaine){
			$file->setFileName('commande_'.date('d/m/Y', strtotime($semaine[0])));
			$sheets = [0 => 'primaire', 1 => 'maternelle'];
			foreach ($sheets as $nbsheet => $sheet) {
				$lignes = [14 => 'total', 15 => 'porc', 16 => 'viande'];
				foreach ($lignes as $key => $ligne) {
					$type = $ligne;
					$total = 0;
			        for($i=1; $i<=5; $i++){
			        	$cellule = (chr(($i-1)*4+1+ord('A'))).$key;
			        	$file->setActiveSheetIndex($nbsheet)->setCellValue($cellule, $data[$i][$sheet][$type]);
			        	$total += $data[$i][$sheet][$type];
			        }
			        // Remplissage dates et total
			        $file->setActiveSheetIndex($nbsheet)->setCellValue('V'.$key, $total);
			        $file->setActiveSheetIndex($nbsheet)->setCellValue('E4',date('d/m/Y'));
			        $file->setActiveSheetIndex($nbsheet)->setCellValue('M9', 'S'.date('W'));
			        $file->setActiveSheetIndex($nbsheet)->setCellValue('O9',date('d/m/Y',strtotime($semaine[0])));
			        $file->setActiveSheetIndex($nbsheet)->setCellValue('R9',date('d/m/Y',strtotime($semaine[1])));
			        //remplissage evictions en bas
			        $table_jours = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi'];
			        $ligne = 21;
			        //dd($data['eviction']);
			        if(isset($data['eviction']) && isset($data['eviction'][$sheet])){
				        foreach($data['eviction'][$sheet] as $eviction){
				        	$file->setActiveSheetIndex($nbsheet)->setCellValue('A'.$ligne ,$eviction['enfant']->nom);
				        	$file->setActiveSheetIndex($nbsheet)->setCellValue('C'.$ligne ,$eviction['enfant']->prenom);
				        	$file->setActiveSheetIndex($nbsheet)->setCellValue('F'.$ligne ,$eviction['enfant']->exception_autre);
				        	$jour_exep = '';
				        	foreach ($eviction['jour'] as $j) {
				        		if($j != ""){
				        			$jour_exep .= $table_jours[$j-1].' ';
				        		}
				        	}
				        	$file->setActiveSheetIndex($nbsheet)->setCellValue('P'.$ligne , $jour_exep);
				        	$ligne++;
				        }
				    }
				}	
	    	}
	    	//feuille recap
	    	$file->setActiveSheetIndex(2)->setCellValue('A1', 'Commande repas S'.date('W',strtotime($semaine[0])));
	    	$file->setActiveSheetIndex(2)->setCellValue('B3', date('d/m/Y',strtotime($semaine[0])));
	    	$file->setActiveSheetIndex(2)->setCellValue('D3', date('d/m/Y',strtotime($semaine[1])));
	    	$file->setActiveSheetIndex(2)->setCellValue('B15', date('d/m/Y'));
	    	for ($i=1; $i <= 5; $i++) { 
	    		$total = $data[$i]['primaire']['fixe']+$data[$i]['maternelle']['fixe']+
	    			$data[$i]['primaire']['exceptionnelle']+$data[$i]['maternelle']['exceptionnelle'];
	    			
	    		$file->setActiveSheetIndex(2)->setCellValue(chr($i+65).'7', $data[$i]['primaire']['fixe'] - $data[$i]['adulte']['fixe']);
	    		$file->setActiveSheetIndex(2)->setCellValue(chr($i+65).'6', $data[$i]['maternelle']['fixe']);
	    		//ajouter prof et personnelle
	    		$file->setActiveSheetIndex(2)->setCellValue(chr($i+65).'8',  $data[$i]['adulte']['instituteur']);
	    		$file->setActiveSheetIndex(2)->setCellValue(chr($i+65).'9', $data[$i]['adulte']['personnelle']);
	    		$file->setActiveSheetIndex(2)->setCellValue(chr($i+65).'10', $data[$i]['primaire']['exceptionnelle']+$data[$i]['maternelle']['exceptionnelle']);
	    		//dd('=Somme('.chr($i+65).'6:'.chr($i+65).'10)');
	    		$file->setActiveSheetIndex(2)->setCellValue(chr($i+65).'11', $total);
	    	}
	    })->export('xls');
	}

	public function exportFiches($type, $jour)
	{
		$tabVacances = Feries::chargement();
		$semaine = [$jour, date('Y-m-d',strtotime(date('Y-m-d', strtotime($jour))."+4 day"))];
		if($type == 'garderie'){
			$this->exportGarderie ($semaine);

		}else if($type == 'cantine'){
			$this->exportCantine ( $tabVacances, $semaine);

		}else if($type == 'bus_matin' || $type == 'bus_soir'){
			$this->exportBus ($type, $semaine);

		}
	}
	/**
	 * 
	 */private function exportBus($type, $semaine) {
		$regs = Reguliere::whereHas('enfant', function($q){
			return $q->orderBy('enfants.arret_id');
		})
			->with(['enfant', 'enfant.classe', 'enfant.arret', 'enfant.arret.ligne'])
			->where('jours', 'LIKE', '%'.date('N', strtotime($semaine[0])).'%')
			->where('type', $type)
			->get();

		//dd($regs, date('N', strtotime($semaine[0])));	
		$exeps = Exceptionnelle::with('enfant', 'enfant.classe')
			->where('jour', date('Y-m-d', strtotime($semaine[0])))
			->where('type', $type)
			->get();

		foreach($regs as $reg){
			if($reg->enfant != null && $reg->enfant->arret != null){
				$inscrits[$reg->enfant->arret->ligne->id][$reg->enfant->arret->numero_arret][$reg->enfant_id] = $reg;
				if(!isset($inscrits[$reg->enfant->arret->ligne->id]['nom'])){
					$inscrits[	$reg->enfant->arret->ligne->id]['nom'] = $reg->enfant->arret->ligne;
				}
				if(!isset($inscrits[$reg->enfant->arret->ligne->id][$reg->enfant->arret->numero_arret]['nom'])){
					$inscrits[$reg->enfant->arret->ligne->id][$reg->enfant->arret->numero_arret]['nom'] = $reg->enfant->arret;
				}
			}
		}
		foreach ($exeps as $exep) {
			if($reg->enfant != null && $reg->enfant->arret != null && $exep->enfant->arret){
				//if($exep->inscrit === 1){// Fred : 13/12/15 correction de bug propagée, mettre entre ' les comparaisons d'inscrits
				if($exep->inscrit === '1'){	
					$inscrits[$exep->enfant->arret->ligne->id][$exep->enfant->arret->numero_arret][$exep->enfant_id] = $exep;
					if(!isset($inscrits[$exep->enfant->arret->ligne->id]['nom'])){
						$inscrits[	$exep->enfant->arret->ligne->id]['nom'] = $exep->enfant->arret->ligne;
					}
					if(!isset($inscrits[$exep->enfant->arret->ligne->id][$exep->enfant->arret->numero_arret]['nom'])){
						$inscrits[$exep->enfant->arret->ligne->id][$exep->enfant->arret->numero_arret]['nom'] = $exep->enfant->arret;
					}
				}else{
					unset($inscrits[$exep->enfant->arret->ligne->id][$exep->enfant->arret->numero_arret][$exep->enfant_id]);
				}
			}
		}
// 			dd($inscrits);
		foreach ($inscrits as $ligne) {
			$temp = $ligne['nom'];
			unset($ligne['nom']);
			ksort($ligne, SORT_NUMERIC);
			$ligne['nom'] = $temp;
		}
// 			dd($inscrits);
		//dd($type.'_'.date('d/m/Y',strtotime($semaine[0])));
		Excel::create('test', function($file)use($inscrits, $semaine, $type){
			$file->setFileName($type.'_'.date('d/m/Y',strtotime($semaine[0])));
			foreach ($inscrits as $key => $lignes) {
				$file->sheet($lignes['nom']->nom, function($sheet)use($lignes, $semaine, $type){
					$data = [
						['',ucfirst(str_replace('_', ' ', $type)),'Semaine '. date('w',strtotime($semaine[0])), 'le ', date('d/m/Y',strtotime($semaine[0]))],
						['',$lignes['nom']->nom, $lignes['nom']->communes],
						['','Nombre d\'enfants', '' ,'sur', $lignes['nom']->nb_place],
						['','Nom', 'Prenom','Autoris.','','','Responsables']
					];
					$l = 5;
					$total = 0;
					foreach ($lignes as $cle => $arrets) {
						if($cle !== 'nom'){
							$data[] = ['>>', $arrets['nom']->numero_arret, $arrets['nom']->nom, $arrets['nom']->commune, count($arrets)-1];
							$sheet->cells('A'.$l.':G'.$l, function($cell){
								$cell->setBackground('#ffff00');
								$cell->setBorder('thin', 'none','thin', 'none');
								$cell->setAlignment('center');
								$cell->setFont(array(
							        'family'     => 'Calibri',
							        'size'       => '14',
							        'bold'       =>  true
							    ));
							});
							$l++;
							$total += count($arrets)-1;
							foreach ($arrets as $cle2 => $enfant) {
								if($cle2 != 'nom'){
									//dd($enfant);
									$data[] = [' ', $enfant->enfant->nom, $enfant->enfant->prenom,
									 ($enfant->enfant->rentre_seul)? 'OUI':'',
									 ($enfant->enfant->classe != null && $enfant->enfant->classe->cycle == 'Maternelle')? 'M':'',
									 '', $enfant->enfant->responsable_bus];
									$sheet->setBorder('A'.$l.':F'.$l, 'thin', 'thin','thin', 'thin');
									$l++;
								}
							}
						}
					}
					$data[2][2] = $total;
					$sheet->cells('A1:G4', function($cell){
						$cell->setBackground('#ffff00');
						$cell->setFont(array(
							        'family'     => 'Calibri',
							        'size'       => '14',
							        'bold'       =>  true
							    ));
					});
					$sheet->setBorder('E1', 'thin');
					$sheet->setBorder('A4:G4', 'thin');
					$sheet->setWidth(array(
				        'A'     =>  3,
				        'B'     =>  40,
				        'C'		=>  40,
				        'D' 	=>  15,
				        'E'		=>  15,
				        'G'		=>  50
				    ));
					$sheet->fromArray($data, null, 'A1', false, false);
				});
			}

		})->export('xls');
	}

	/**
	 * @param tabVacances
	 * @param semaine
	 */private function exportCantine($tabVacances, $semaine) {
		$regs = Reguliere::with(['Enfant', 'Enfant.Classe'])
			->where('type', 'cantine')
			->get();
		$exeps = Exceptionnelle::with(['Enfant', 'Enfant.Classe'])
			->whereBetween('jour', $semaine)
			->where('type', 'cantine')
			->get();

		$ferie = [];
		for ($i=0; $i < 5 ; $i++) { 
			$monjour = date('Y-m-d', strtotime($i.'day'.$semaine[0]));
			if(Feries::est_ferie($monjour) || Feries::est_vacances($monjour, $tabVacances)){
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
			//if($exep->inscrit === 1){ // Fred : 13/12/15 correction de bug propagée, mettre entre ' les comparaisons d'inscrits
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
		Excel::load('fichiers/cantine.xlsx', function($file)use($inscrits, $semaine){
			$cycles = ['primaire', 'maternelle', 'adulte'];
			$file->setFileName('cantine'.date('d/m/Y', strtotime($semaine[0])));
			for($i=0; $i<3; $i++){
				$cycle = $cycles[$i];
				$file->setActiveSheetIndex($i)->setCellValue('A3', 'S'.date('W', strtotime($semaine[0])));
				$file->setActiveSheetIndex($i)->setCellValue('C3', date('d/m/Y', strtotime($semaine[0])));
				$file->setActiveSheetIndex($i)->setCellValue('D3', date('d/m/Y', strtotime($semaine[1])));
			}
			$ligne = [6,6,6];
			foreach($inscrits as $inscrit){
				//if($inscrit['enfant']->enfant_id == 32){dd($inscrit['enfant']->enfant->classe->cycle);}
				
				$cycle = $inscrit['enfant']->enfant->classe->cycle;
				switch ($cycle) {
					case 'Primaire':
						$feuille = 0;
						break;
					case 'Maternelle':
						$feuille = 1;
						break;
					default:
						$feuille = 2;
						break;
				}
				$file->setActiveSheetIndex($feuille)->setCellValue('A'.$ligne[$feuille], $inscrit['enfant']->enfant->classe->niveau);
				$file->setActiveSheetIndex($feuille)->setCellValue('B'.$ligne[$feuille], $inscrit['enfant']->enfant->classe->instituteur);
				$file->setActiveSheetIndex($feuille)->setCellValue('C'.$ligne[$feuille], $inscrit['enfant']->enfant->nom);
				$file->setActiveSheetIndex($feuille)->setCellValue('D'.$ligne[$feuille], $inscrit['enfant']->enfant->prenom);
				for($j=1; $j<=5; $j++){
					if(in_array($j, $inscrit['inscription'])){
						$file->setActiveSheetIndex($feuille)->setCellValue((chr(ord('D')+$j).$ligne[$feuille]), 'F');
					}
				}
				$ligne[$feuille]++;
			}
		})->export('xls');
	}

	/**
	 * @param semaine
	 * 
	 */private function exportGarderie($semaine) {
		Excel::load('fichiers/garderie.xlsx',function($file)use($semaine){
// Attention : l'ordre des feuilles Excel a une importance : 1- Maternelle, 2 - Primaire
			for($i=0; $i<2; $i++){
				$cycles = ['primaire', 'maternelle'];
				$cycle = $cycles[$i];
				$enfants = Enfant::where('garderie',true)->whereHas('classe', function($q)use($cycle){
					return $q->where('cycle', $cycle);
				})->get();
				$nb = $enfants->count();
				$file->setFileName('garderie'.date('d/m/Y', strtotime($semaine[0])));
				$file->setActiveSheetIndex($i)->setCellValue('D2', 'S'.date('W', strtotime($semaine[0])));
				$file->setActiveSheetIndex($i)->setCellValue('E6', date('d/m/Y', strtotime($semaine[0])));
				$file->setActiveSheetIndex($i)->setCellValue('K6', date('d/m/Y', strtotime($semaine[1])));
				$ligne = 10;
				foreach($enfants as $cle => $enfant){
					$file->setActiveSheetIndex($i)->setCellValue('A'.$ligne, $enfant->classe->niveau);
					$file->setActiveSheetIndex($i)->setCellValue('B'.$ligne, $enfant->nom);
					$file->setActiveSheetIndex($i)->setCellValue('C'.$ligne, $enfant->prenom);
					$ligne++;
				}
			}
		})->export('xlsx');
	}


	public function facturation(Request $request)
	{
		$data = $request->all();
		$mois = date('Y-m-d', strtotime($data['mois']));
		$fin = date('Y-m-d', strtotime('+2 month', strtotime($mois)));
		$facturations = Facturation::with('enfant', 'enfant.classe')->whereBetween('semaine', [$mois, $fin])->orderBy('enfant_id')->get();
		//dd($facturations, $mois, $fin);
		Excel::load('fichiers/facturation.xlsx', function($file)use($mois, $fin, $facturations, $data){
			$file->setFileName('facturation_'.Feries::Mois(date('n', strtotime('+10day'.$mois))).'_'.Feries::Mois(date('n', strtotime('+1month'.$mois))));
			$file->setActiveSheetIndex(0)->setCellValue('E3', date('d/m/Y', strtotime($mois)));
			$file->setActiveSheetIndex(0)->setCellValue('E4', date('d/m/Y', strtotime($fin)));
			$file->setActiveSheetIndex(0)->setCellValue('E6', $data['prix_elementaire']);
			$file->setActiveSheetIndex(0)->setCellValue('D6', $data['prix_maternelle']);
			$ligne = 10;
			$id = 0;
			$total = 0;
			$colonne = 'G';
			foreach ($facturations as $facturation) {
				if($id != $facturation->enfant_id){
					if($total != 0){
						$file->setActiveSheetIndex(0)->setCellValue('E'.$ligne, $total);
						if($last->enfant->classe->cycle == 'Maternelle'){
							$file->setActiveSheetIndex(0)->setCellValue('F'.$ligne, $total * $data['prix_maternelle']); 
							//dd($facturation, $total, 'F'.$ligne,  $data['prix_maternelle']);
						}else{
							$file->setActiveSheetIndex(0)->setCellValue('F'.$ligne, $total * $data['prix_elementaire']);
						}
						$total = 0;
					}
					$ligne++;
					//$maDate = $mois;
					$colonne = 'G';
					$file->setActiveSheetIndex(0)->setCellValue('A'.$ligne, $facturation->enfant->classe->niveau);
					$file->setActiveSheetIndex(0)->setCellValue('B'.$ligne, $facturation->enfant->classe->instituteur);
					$file->setActiveSheetIndex(0)->setCellValue('C'.$ligne, $facturation->enfant->nom);
					$file->setActiveSheetIndex(0)->setCellValue('D'.$ligne, $facturation->enfant->prenom);
					$id = $facturation->enfant_id;
					//ici prix
				}
				$maDate = $mois;
				while($maDate < $facturation->semaine){
					$maDate = date('Y-m-d',strtotime('+7day'.$maDate));
					$colonne = chr(ord($colonne)+3);
				}

				$total += $facturation->fixe + $facturation->non_fixe;
				$file->setActiveSheetIndex(0)->setCellValue($colonne.$ligne, $facturation->fixe + $facturation->non_fixe);
				$file->setActiveSheetIndex(0)->setCellValue(chr(ord($colonne)+1).$ligne, 0);
				$file->setActiveSheetIndex(0)->setCellValue(chr(ord($colonne)+2).$ligne, $facturation->abscence_signalee);
				$colonne = 'G';
				$last = $facturation;
			}
			$file->setActiveSheetIndex(0)->setCellValue('E'.$ligne, $total);
			if(isset($last)){
				if($last->enfant->classe->cycle == 'maternelle'){
					$file->setActiveSheetIndex(0)->setCellValue('F'.$ligne, $total * $data['prix_maternelle']); 
				}else{
					$file->setActiveSheetIndex(0)->setCellValue('F'.$ligne, $total * $data['prix_elementaire']);
				}
			}
		})->export('xls');
	}
}
