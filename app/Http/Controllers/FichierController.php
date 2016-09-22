<?php namespace App\Http\Controllers;

use Excel;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Enfant;
use App\Classe;
use Illuminate\Http\Request;
use App\Http\Requests\EnfantsRequest;
use App\Reguliere;
use App\Exceptionnelle;

class FichierController extends Controller {


	public function __construct()
	{
		$this->middleware('admin');
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function exporter()
	{
		$request = Enfant::get(array('nom','prenom','classe_id'));
		$test = Excel::create('test', function($excel) use ($request){
			$excel->sheet('Page', function($sheet) use ($request){
				$sheet->fromArray($request);
			});
		})->export('xls');

	}

}
