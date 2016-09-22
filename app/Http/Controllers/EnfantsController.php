<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Enfant;
use App\Classe;
use App\Arret;
use Illuminate\Http\Request;
use App\Http\Requests\EnfantsRequest;

class EnfantsController extends Controller {


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
// 		$enfants = Enfant::with('classe')->paginate(20);
	// Tri selon le classe_id. Pas terrible.
		$enfants = Enfant::with('classe')->where('niveau_classe','<>','EX')
			->orderBy('classe_id')->paginate(20);
		$enfants->setPath('enfants');
		return view('enfants.listeEnfants', compact('enfants'));
	}

	public function indexByClass($class){
		$classe = Classe::findOrFail($class);
		$enfants = $classe->enfants()->paginate(20);
		$path = 'enfants/'.$class;
		$enfants->setPath($path);
		return view('enfants.listeEnfants', compact('enfants'));
	}

	public function indexAllClass(){
		$enfants = Enfant::orderBy('classe_id')->paginate(20);
		$enfants->setPath('enfants');
		return view('enfants.listeEnfants', compact('enfants'));
	}
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$enfant = new Enfant();
		$arrets = Arret::lists('nom','id');
		$classes = Classe::lists('niveau', 'id');
		$instituteur = Classe::lists('instituteur', 'id');
		return view('enfants.form', compact('enfant', 'classes', 'instituteur', 'arrets'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(EnfantsRequest $request)
	{
		$data = $request->all();
		$data['naissance'] = $data['annee'].'-'.$data['mois'].'-'.$data['jour'];
		Enfant::create($data);
		return redirect(route('enfants.index'));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$enfant = Enfant::findOrFail($id);
		return view('enfants.afficher',compact('enfant'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$arrets = Arret::lists('nom','id');
		$enfant = Enfant::findOrFail($id);
		$classes = Classe::lists('niveau', 'id');
		$instituteur = Classe::lists('instituteur', 'id');
		return view('enfants.form', compact('enfant', 'classes', 'instituteur', 'arrets'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, EnfantsRequest $request)
	{
		$enfant = Enfant::findOrFail($id);
		$data = $request->all();
		$data['naissance'] = $data['annee'].'-'.$data['mois'].'-'.$data['jour'];
		$enfant->update($data);
		return redirect(route('enfants.show', $id));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$enfant = Enfant::findOrFail($id);
		$enfant->delete();
		return redirect(route('enfants.index'));
	}

}
