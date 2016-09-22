<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Ligne;
use Illuminate\Http\Request;

class LigneController extends Controller {


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
		$lignes = Ligne::orderBy('nom')->get();
		return view('lignes.index', compact('lignes'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$ligne = new Ligne();
		return view('lignes.form', compact('ligne'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$data = $request->all();
		$ligne = Ligne::create($data);
		return redirect(route('ligne.show',$ligne));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$ligne = Ligne::with('arrets')->findOrFail($id);
		$arrets = $ligne->arrets()->get();
		return view('lignes.show', compact('ligne', 'arrets'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$ligne = Ligne::findOrFail($id);
		return view('lignes.form', compact('ligne'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$ligne = Ligne::findOrFail($id);
		$ligne->update($request->all());
		return redirect(route('ligne.show',$ligne));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$ligne = Ligne::findOrFail($id);
		$ligne->delete();
		return redirect(route('ligne.index'));
	}

}
