<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\ParentRequest;
use App\Library\Generator;

class PersonnesController extends Controller {


public function __construct()
	{
		$this->middleware('adsem');
		$this->middleware('admin', ['only' => ['create', 'destroy']]);
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$personnes = User::orderBy('nom')->paginate(20);
		$personnes->setPath('personnes');
		return view('personnes.listePersonnes', compact('personnes'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('personnes.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(ParentRequest $request)
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
		$personne = User::create($data);
		return view('personnes.recap', compact('personne'), compact('mdp'));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$personne = User::findOrFail($id);
		return view('personnes.afficher',compact('personne'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$personne = User::findOrFail($id);
		return view('personnes.edit', compact('personne'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, ParentRequest $request)
	{
		$personne = User::findOrFail($id);
		$data = $request->all();
		if(($data['password'])==''){
			unset($data['password']);
		}else{
			$data['password'] = bcrypt($data['password']);
		}
		$personne->update($data);
		return redirect(route('personnes.show', $id));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$personne = User::findOrFail($id);
		$personne->delete();
		return redirect(route('personnes.index'));
	}

}
