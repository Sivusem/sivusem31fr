<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Enfant;

use Illuminate\Http\Request;

class RechercheController extends Controller {

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
		return view('admin.rechercher');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		if ( $request->type == 'parent'){
			$personnes = User::where('nom','LIKE', '%'.$request->nom.'%')->where('prenom', 'LIKE', '%'.$request->prenom.'%');
			//dd($personnes);
			if($request->ville != ''){
				$personnes = $personnes->where('ville', 'LIKE', '%'.$request->ville.'%');
			}
			$personnes = $personnes->paginate(20);
			return view('personnes.listePersonnes', compact('personnes'));
		}
		else{
			$enfants = Enfant::where('nom','LIKE', '%'.$request->nom.'%')->where('prenom', 'LIKE', '%'.$request->prenom.'%');
			if($request->ville != ''){
				$enfants = $enfants->whereHas('users', function($q) use($request){
					return $q->where('ville', 'LIKE', '%'.$request->ville.'%');
				});
			}
			if($request->instituteur != ''){
				$enfants = $enfants->whereHas('classe', function($q) use($request){
					return $q->where('instituteur', 'LIKE', '%'.$request->instituteur.'%');
				});
			}
			$enfants = $enfants->paginate(20);
			return view('enfants.listeEnfants',compact('enfants'));
		}
		
		
	}

}