<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Facturation;
use App\User;
use App\Enfant;
use App\Exceptionnelle;
use App\Reguliere;
use App\Personne;
use App\Classe;
use Excel;
use App\Arret;
use App\Ligne;
use App\Http\Requests\FichierRequest;
use App\Library\Generator;
use App\Library\Feries;
use DB;

class CreationController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function __construct()
	{
		$this->middleware('admin');
	}
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function affichageCreation()
	{
		return view('creation.afficherCreate');
	}

	public function supprimerBase()
	{
		$enfants = Enfant::orderBy('classe_id')->with('users')->with('classe')
			->with('arret')->with('regulieres')->get();
		$enfants_parents[0] = ['Classe', 'Nbre élèves', 'Commune', 'Nom', 'Prénom', 'Instituteur','CANTINE','GARDERIE','TRANSPORT',
			'AUTORISATION', 'arret', 'porc', 'viande', 'PAI', 'nb_parent','nom1', 'prenom1', 'adresse1', 'nom2', 'prenom2', 'adresse2'
		];
		$cantine[0] = ['Nom', 'Prenom',	'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Jour_Debut'];
		$bus[0] = ['Nom', 'Prenom', 'Lundi_matin', 'Lundi_soir', 'Mardi_matin', 'Mardi_soir', 'Mercredi_matin', 
			'Mercredi_soir', 'Jeudi_ matin', 'Jeudi_soir', 'Vendredi_matin', 'Vendredi_soir', 'Jour_Debut', 'Responsable1',
			'Responsable2',	'Responsable3', 'Responsable4', 'Responsable5'];
		foreach ($enfants as $enfant) {
			$parents = $enfant->users()->get();
			$nb_parent = $parents->count();
			$enfants_parents[$enfant->id] = [
				$enfant->niveau_classe, '', '', $enfant->nom, $enfant->prenom, ($enfant->classe != null)? $enfant->classe->instituteur : '', 
				($enfant->mange_cantine)? 'OUI' : 'NON', ($enfant->garderie)? 'OUI' : 'NON', ($enfant->prendre_bus)? 'OUI' : 'NON',
				($enfant->rentre_seul)? 'OUI' : 'NON', ($enfant->prendre_bus && $enfant->arret != null)? $enfant->arret->nom : '', 
				($enfant->exception_porc)? 'OUI' : 'NON', ($enfant->exception_viande)? 'OUI' : 'NON', $enfant->exception_autre, $nb_parent
			];
			$regs = $enfant->regulieres()->get();
			$bus[$enfant->id] = [$enfant->nom, $enfant->prenom];
			$bus[$enfant->id][13] = $enfant->responsable_bus;
			$cantine[$enfant->id] = [$enfant->nom, $enfant->prenom];
			foreach ($regs as $reg) {
				$jours = str_split($reg->jours);
				for($i = 1; $i<=5; $i++){
					$type = $reg->type;
					if(in_array($i, $jours)){
						if($type == 'cantine'){
							$cantine[$enfant->id][$i + 1] = 'x';
						}else if($type == 'bus_matin'){
							//dd($jours, $enfant, $reg, $i*2);
							$bus[$enfant->id][$i*2] = 'x';
						}else{
							//dd(($i+1)*2+2,$enfant);
							$bus[$enfant->id][$i*2+1] = 'x';
						}
					}else{
						if($type == 'cantine'){
							$cantine[$enfant->id][$i + 1] = ' ';
						}else if($type == 'bus_matin'){
							//dd($jours, $enfant, $reg, $i*2);
							$bus[$enfant->id][$i*2] = ' ';
						}else{
							//dd(($i+1)*2+2,$enfant);
							$bus[$enfant->id][$i*2+1] = ' ';
						}
					}
				}
			}
			foreach ($parents as $p) {
				$enfants_parents[$enfant->id][] = $p->nom;
				$enfants_parents[$enfant->id][] = $p->prenom;
				$enfants_parents[$enfant->id][] = $p->email;
			}
			ksort($bus[$enfant->id]);
			//dd($bus[$enfant->id]);
		}
		
		$classes = Classe::where('cycle', '<>', 'adulte')->get();
		$classe[0] = ['Classe', 'cycle', 'instituteur'];
		foreach ($classes as $c) {
			$classe[$c->id] = [$c->niveau, $c->cycle, $c->instituteur];
		}
		$arrets = Arret::with('ligne')->get();
		$arret[0] = ['Ligne', 'Arret', 'Numero', 'Commune'];
		foreach ($arrets as $a) {
			$arret[$a->id] = [$a->ligne->nom, $a->nom, $a->numero_arret, $a->commune];
		}
		DB::statement("SET foreign_key_checks=0");
		Arret::truncate();
		Ligne::truncate();
		Exceptionnelle::truncate();
		Reguliere::truncate();
		$classe = Classe::where('cycle', '<>', 'adulte')->lists('id');
		Classe::destroy($classe);
		Facturation::truncate();
		$users = User::where('niveau', 0)->lists('id');
		User::destroy($users);
		Enfant::truncate();
		DB::table('enfant_user')->truncate();
		DB::statement("SET foreign_key_checks=1");
		//dd($bus);
		Excel::create('fin_annee'.date('Y'), function($file) use ($enfants_parents, $cantine, $bus, $classe, $arret){
			$file->sheet('Classes', function($sheet)use($classe){
				$sheet->fromArray( $classe, null, 'A1', false, false);
			});
			$file->sheet('Sivusem', function($sheet)use($enfants_parents){
				$sheet->fromArray( $enfants_parents, null, 'A1', false, false);
			});
			$file->sheet('Lignes', function($sheet)use($arret){
				$sheet->fromArray( $arret, null, 'A1', false, false);
			});
			$file->sheet('Cantine', function($sheet)use($cantine){
				$sheet->fromArray( $cantine, null, 'A1', false, false);
			});
			$file->sheet('Bus', function($sheet)use($bus){
				$sheet->fromArray( $bus, null, 'A1', false, false);
			});
		})->export('xls');
	}

	public function importerFichiers( Request $request)
	{
		ini_set('max_execution_time',1000);
		$file = $request->file('importer');
		$destination = __DIR__.'uploads';
		$name = $file->getClientOriginalName();
		$file->move($destination, $name);
		$nom = $destination.'/'.$name;
		$tabVacances = Feries::chargement();

		$nombre_personnes = 0;
		Excel::selectSheets('Classes')->load($nom,function($reader){
			$liste_classes = $reader->limit(500)->get();
			foreach ( $liste_classes as $class){
				$classe = new Classe;
				$classe->niveau = $class['classe'];
				$classe->instituteur = $class['instituteur'];
				if ( ucfirst($class['cycle']) == 'Elementaire' ||  ucfirst($class['cycle']) == 'Primaire'){
					$classe->cycle = 'Primaire';
				}else{
					$classe->cycle = 'Maternelle';
				}
				$classe->save();
			}
		});
		Excel::selectSheets('Lignes')->load($nom,function($reader){
			$liste_ligne = $reader->limit(500)->get();
			foreach ( $liste_ligne as $liste){
				$arret = new Arret;
				$arret->nom = $liste['arret'];
				$arret->numero_arret = $liste['numero'];
				$ligne = Ligne::where('nom',$liste['ligne'])->first();
				if ( empty($ligne) ){
					$ligne = new Ligne;
					$ligne->nom = $liste['ligne'];
					$ligne->save();
				}
				$arret->ligne_id = $ligne->id;
				$arret->save();
			}
		});

		$test = Excel::selectSheets('Sivusem')->load($nom,function($sheet) use (&$data){
			$enfants = $sheet->limit(500)->get();
			foreach ( $enfants as $fic){
				if ( $fic['nom'] != null || $fic['prenom'] != null){
					$user = new Enfant;
					if ( $fic['nom'] == null ){ // MODIF 2
						$user->nom = "FAUX";
					}
					else{
						$user->nom = $fic['nom'];
					}
					$user->prenom = $fic['prenom'];
					$classe = Classe::where('instituteur',$fic['instituteur'])->first();
					if($classe != null){
						$user->classe_id = $classe->id;
					}
					$user->niveau_classe = $fic['classe'];
					if ( strtolower($fic['garderie']) == 'oui'){
						$user->garderie = 1;
					}
					else{
						$user->garderie = 0;
					}
					if ( strtolower($fic['transport']) == 'oui'){
						$user->prendre_bus = 1;
						if ( strtolower($fic['autorisation']) == 'oui'){
							$user->rentre_seul = 1;
						}
						else{
							$user->rentre_seul = 0;
						}
						$arret = Arret::where('nom',$fic['arret'])->first();
						if($arret != null){
							$user->arret_id = $arret->id;
						}
					}
					else{
						$user->prendre_bus = 0;
					}
					//$user->arret_id = $arret->id;
					if ( strtolower($fic['cantine']) == 'oui'){
						$user->mange_cantine = 1;
						if ( strtolower($fic['porc']) == 'non' ){
							$user->exception_porc = false;
						}
						else{
							$user->exception_porc = true;
						}
						if ( strtolower($fic['viande']) == 'non'){
							$user->exception_viande = false;
						}
						else{
							$user->exception_viande = true;
						}
						if ( $fic['pai'] == null ){
							$user->exception_autre = "";
						}
						else{
							$user->exception_autre = $fic['pai'];
						}
					}
					else{
						$user->mange_cantine = 0;
					}
						$user->save();
						$cantine = new Reguliere;
						$cantine->type = 'cantine';
						$cantine->enfant_id = $user->id;
						$cantine->save();
						$bus_matin = new Reguliere;
						$bus_matin->type = 'bus_matin';
						$bus_matin->enfant_id = $user->id;
						$bus_matin->save();
						$bus_soir = new Reguliere;
						$bus_soir->type = 'bus_soir';
						$bus_soir->enfant_id = $user->id;
						$bus_soir->save();
						//$parent1_exist = User::where('nom',$fic['nom1'])->where('prenom',$fic['prenom1'])->get();
						//$parent2_exist = User::where('nom',$fic['nom2'])->where('prenom',$fic['prenom2'])->get();
						$parent1_exist = User::where('email',$fic['adresse1'])->get();
						$parent2_exist = User::where('email',$fic['adresse2'])->get();

					for ( $nombre = 0 ; $nombre < $fic['nb_parent'] ; $nombre++){
							if ( $nombre == 1){
								if ( $parent2_exist->count() == 0){
									$parent = new User;
									$parent->nom = ($fic['nom2'] == NULL)?'FAUX' : $fic['nom2'];
									$parent->prenom = $fic['prenom2'];
									if ( $fic['adresse2'] != $fic['adresse1'] ){
										$parent->email = $fic['adresse2'];
									}
								}	
							}
							else{
								if ( $parent1_exist->count() == 0){
									$parent = new User;
									$parent->nom = ($fic['nom1'] == NULL)?'FAUX' : $fic['nom1'];
									$parent->prenom = $fic['prenom1'];
									$parent->email = $fic['adresse1'];
								}
								
							}
							if ( $parent1_exist->count() == 0 && $parent2_exist->count() == 0){
								$parent->niveau = 0;
								$nb = 0;
								do{
									$id = Generator::generate_id($parent->nom,$parent->prenom, $nb);
									$nb++;
								}while(User::where('identifiant',$id)->count() != 0);
								$parent->identifiant = $id;
								$mdp = Generator::mdp();
								$data[] = ['nom' => $parent->nom , 'prenom' => $parent->prenom , 'email' => $parent->email,'identifiant' => $parent->identifiant , 'password' => $mdp];
								$parent->password = bcrypt($mdp);
								$parent->save();
								$user->users()->attach([$parent->id]);
							}
							else{
								if ( $parent1_exist->count() != 0){
									$user->users()->attach([$parent1_exist->first()->id]);
								}
								if ( $parent2_exist->count() != 0){
									$user->users()->attach([$parent2_exist->first()->id]);
								}
							}
					}
				}
				else{
					break;
				}
			}		
		});
		Excel::selectSheets('Cantine','Bus')->load($nom,function($reader)use($tabVacances){
			$cantines = $reader->limit(500)->get();
			$ligne_vide = 0;
			for ( $i = 0 ; $i < 2 ; $i++ ){
				foreach ( $cantines[$i] as $cantine){
					if ( $ligne_vide <= 1){
						if ( $cantine['nom'] == null && $cantine['prenom'] == null){
							$ligne_vide++;
						}
						else{
							$enfant = Enfant::where('nom',($cantine['nom'] == null)? 'FAUX' : $cantine['nom'])->where('prenom',$cantine['prenom'])->first();
							if ( $i == 0){
								if ( $cantine['jour_debut'] != null){ // MODIF 3
									$rentree = Feries::jour_rentree(date('Y',strtotime($cantine['jour_debut'])), $tabVacances);
									$debut_rentree = date('Y-m-d',strtotime($rentree));
									$debut_inscrit = date('Y-m-d',strtotime($cantine['jour_debut']));
								}
								if($enfant == null){
									dd($cantine, $cantine['nom'] );
								}
								$reg = Reguliere::where('enfant_id',$enfant->id)->where('type','cantine')->first();
								 
								$jour = 1;
								$finSemaine = 5;
								$tab = [];
								while ( $jour <= $finSemaine ){
									$day = Feries::Jours($jour);
									if ( $cantine['jour_debut'] != null){ // MODIF 4
										if ( $rentree != $debut_rentree ){
											while ( $debut_rentree < $debut_inscrit){
												if ( date('N',strtotime($debut_rentree)) == $jour){
													if($cantine[$day] != null){
														$excep = new Exceptionnelle;
														$excep->type = 'cantine';
														$excep->enfant_id = $enfant->id;
														$excep->modificate_by = 0;
														$excep->jour = date('Y-m-d',strtotime($debut_rentree));
														$excep->inscrit = 0;
														$excep->save();
													}
												}
												$debut_rentree = date('Y-m-d',strtotime('+1 days',strtotime($debut_rentree)));
												
											}
											$debut_rentree = date('Y-m-d',strtotime($rentree));
										}
									}
									if ( $cantine[$day] != null){
										$tab[] = $jour;
									}
									$jour++;
								}
								$reg->jours = implode($tab);
								$reg->save();
								unset($tab);
							}
							else{
								if ($cantine['jour_debut'] != null){ // MODIF 5
									$rentree = Feries::jour_rentree(date('Y',strtotime($cantine['jour_debut'])), $tabVacances);
									$debut_rentree = date('Y-m-d',strtotime($rentree));
									$debut_inscrit = date('Y-m-d',strtotime($cantine['jour_debut']));
								}
								//dd($rentree);
								if($enfant == null){
									dd($cantine);
								}
								$reg_matin = Reguliere::where('enfant_id',$enfant->id)->where('type','bus_matin')->first();
								$reg_soir = Reguliere::where('enfant_id',$enfant->id)->where('type','bus_soir')->first();
								$jour = 1;
								$finSemaine = 5;
								$tab_matin[] = "";
								$tab_soir[] = "";
								$enfant->responsable_bus = "";
								while ( $jour <= $finSemaine ){
									if($jour == 1){
										$enfant->responsable_bus.= $cantine['responsable'.$jour];
									}else{
										$enfant->responsable_bus .= '/'.$cantine['responsable'.$jour];
									}
									$day = Feries::Jours($jour);
									//dd($cantine);
									if ( $cantine['jour_debut'] != null){ // MODIF 6
										if ( $rentree != $debut_rentree ){
										while ( $debut_rentree < $debut_inscrit){
												if ( date('N',strtotime($debut_rentree)) == $jour){
													if($cantine[$day.'_matin'] != null){
														$excep_matin = new Exceptionnelle;
														$excep_soir = new Exceptionnelle;
														$excep_matin->modificate_by = 0;
														$excep_soir->modificate_by = 0;
														$excep_matin->type = 'bus_matin';
														$excep_soir->type = 'bus_soir';
														$excep_matin->jour = date('Y-m-d',strtotime($debut_rentree));
														$excep_soir->jour = date('Y-m-d',strtotime($debut_rentree));
														$excep_matin->inscrit = 0;
														$excep_soir->inscrit = 0;
														$excep_matin->enfant_id = $enfant->id;
														$excep_soir->enfant_id = $enfant->id;
														$excep_matin->save();
														$excep_soir->save();
													}
												}
												$debut_rentree = date('Y-m-d',strtotime('+1 days',strtotime($debut_rentree)));
										}
										$debut_rentree = date('Y-m-d',strtotime($rentree));
										}
									}
									if ( $cantine[$day.'_matin'] != null){
										$tab_matin[] = $jour;
									}
									if ( $cantine[$day.'_soir'] != null){
										$tab_soir[] = $jour;
									}
									$jour++;
								}
								$reg_matin->jours = implode($tab_matin);
								

								$reg_matin->save();
								$reg_soir->jours = implode($tab_soir);
								$reg_soir->save();
								unset($tab_matin);
								unset($tab_soir);
							}
						}
					}
					else{
						break;
					}	
				}
				$ligne_vide = 0;
			}		
		});
		$test = Excel::create('Liste_Parents_Codes', function($excel) use ($data){
						$excel->sheet('Codes', function($sheet) use ($data){
						$sheet->fromArray($data);
						});
			})->export('xls');

	}
}
