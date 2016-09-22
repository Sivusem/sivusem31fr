<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Enfant;
use App\Library\Generator;
use Illuminate\Http\Request;

class LiaisonController extends Controller {

	public function __construct()
	{
		$this->middleware('admin');
	}

	public function lier_creer_enfant ($idP, Request $request)
	{
		$data = $request->all();
		$data['naissance'] = $data['annee'].'-'.$data['mois'].'-'.$data['jour'];
		$enfant = Enfant::create($data);
		$user = User::findOrFail($idP);
		$user->enfants()->attach([$enfant->id]);
		return redirect(route('personnes.show',$idP));
	}
	public function lier_creer_parent ($idE, Request $request)
	{
		$data = $request->all(); 
		$mdp = Generator::mdp();
		//envoyer le mot de passe quelque part
		$data['password'] = bcrypt($mdp);
		$nb = 0;
		do{
			$id = Generator::generate_id($data['nom'], $data['prenom'], $nb);
			$nb++;
		}while(User::where('identifiant',$id)->count() != 0);
		$data['identifiant'] = $id;
		$user = User::create($data);
		// dd($data);
		$personne = $data;
		$enfant = Enfant::findOrFail($idE);
		$enfant->users()->attach($user->id);
		return view('personnes.recap', compact('personne', 'mdp'));
	}

	public function afficherAll($id,$type)
	{
		if ( $type == 0 ){
			$personne = Enfant::findOrFail($id);
		}
		else{
			$personne = User::findOrFail($id);
		}
		return view('personnes.rechercheEnfants', compact('personne','type'));
	}

	public function lier($id, $idEnfant)
	{
		$user = User::findOrFail($id);
		//dd($user->enfants()->find($idEnfant));
		if($user->enfants()->find($idEnfant) == null){
			$user->enfants()->attach([$idEnfant]);
		}
		return redirect(route('personnes.show', $id));
	}

	public function update($id, Request $request)
	{
		$personne = User::findOrFail($id);
		$enfants = Enfant::where('nom', 'LIKE', '%'.$request->nom.'%')->where('prenom','LIKE', '%'.$request->prenom.'%')->paginate(20);
		return view('personnes.lierEnfants', compact('personne'), compact('enfants'));
	}

	public function rechercheParent($id,Request $request)
	{
		$enfant = Enfant::findOrFail($id);
		$personnes = User::where('nom', 'LIKE', '%'.$request->nom.'$')->where('prenom','LIKE', '%'.$request->prenom.'%')->paginate(20);
		return view('personnes.lierPersonne', compact('personnes'), compact('enfant'));
	}

}
