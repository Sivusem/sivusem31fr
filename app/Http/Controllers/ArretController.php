<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Arret;
use App\Ligne;

use Illuminate\Http\Request;

class ArretController extends Controller {

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
		// Tri selon l'id des tuples de la table arrets. Il vaudrait mieux sur le nom des lignes.
		$arrets = Arret::with('ligne')->orderBy('arrets.ligne_id')->paginate(20);
		$arrets->setPath('arrets');
		return view('arret.listeArrets', compact('arrets'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */

	public function create()
	{
		$lignes = Ligne::lists('nom', 'id');
		$arret = new Arret();
		return view('arret.create',compact('lignes','arret'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$data = $request->all();
		$arret = Arret::create($data);
		return redirect(route('arret.show',$arret));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$arret = Arret::findOrFail($id);
		return view('arret.detail', compact('arret'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$lignes = Ligne::lists('nom', 'id');
		$arret = Arret::findOrFail($id);
		return view('arret.create', compact('arret','lignes'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$arret = Arret::findOrFail($id);
		$arret->update($request->all());
		return redirect(route('arret.show',$arret));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$arret = Arret::findOrFail($id);
		$arret->delete();
		return redirect(route('arret.index'));
	}


}
