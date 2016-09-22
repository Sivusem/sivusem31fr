<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Classe;
use Illuminate\Http\Request;

class ClasseController extends Controller {


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
		$classes = Classe::orderBy('cycle')->paginate(20);
		$classes->setPath('niveau');
		return view('classe.index', compact('classes'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$classe = new Classe;
		return view('classe.form', compact('classe'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		Classe::create($request->all());
		return redirect(route('niveau.index'));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$classe = Classe::findOrFail($id);
		$enfants = $classe->enfants()->get();
		return view('classe.show', compact('classe', 'enfants'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$classe = Classe::findOrFail($id);
		return view('classe.form', compact('classe'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$classe = Classe::findOrFail($id);
		$classe->update($request->all());
		return redirect(route('niveau.show', $id));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$classe = Classe::findOrFail($id);
		$classe->delete();
		return redirect(route('niveau.index'));
	}

}
