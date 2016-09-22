<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Auth\Guard;
use App\Enfant;
use Illuminate\Http\Request;
use App\Http\Requests\ExceptionnelleRequest;
use App\Exceptionnelle;
use App\Library\Feries;

class ExceptionnelleController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function __construct()
	{
		$this->middleware('parents');
	}


	public function show($idE)
	{
		$day = date('Y-m-d');
		$cantines = Exceptionnelle::with('enfant')->where('enfant_id',$idE)->where('type','cantine')->where('jour','>=',$day)->get();
		$bus_matins = Exceptionnelle::where('enfant_id',$idE)->where('type','bus_matin')->where('jour','>=',$day)->get();
//		$bus_soirs = Exceptionnelle::where('enfant_id',$idE)->where('type','bus_soir')->where('jour','>=',$day)->get();
		$bus_soirs = Exceptionnelle::where('enfant_id',$idE)->where('type','bus_soir')->get();
		$enfant = Enfant::findOrFail($idE);
		return view('calendrier.affichage',compact('cantines','bus_matins','bus_soirs','enfant'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($idE, Guard $auth)
	{
		$user = $auth->user();
		$enfant = Enfant::findOrFail($idE);

//	modif Fred 22/08/2016 : introduction constante USER_LEVEL
//      if($user->enfants()->where('enfants.id', $idE)->count() == 0 && $user->niveau == 0){
		if($user->enfants()->where('enfants.id', $idE)->count() == 0 && $user->niveau == USER_LEVEL){
			return redirect(route('reguliere.index'));
		}
		return view('calendrier.exceptionnelle',compact('idE'));
	}
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($idE, Request $request, Guard $auth)
	{
		$user = $auth->user();
		$enfant = Enfant::findOrFail($idE);
		
//	modif Fred 22/08/2016 : introduction constante USER_LEVEL
// 		if($user->enfants()->where('enfants.id', $idE)->count() == 0 && $user->niveau == 0){
		if($user->enfants()->where('enfants.id', $idE)->count() == 0 && $user->niveau == USER_LEVEL){
			return redirect(route('reguliere.index'));
		}
		$data = $request->all();
		$data['jour'] = date('Y-m-d',strtotime($data['jour']));
		$data['enfant_id'] = $idE;
		$data['modificate_by'] = $user->niveau;
		$days = Exceptionnelle::where('jour',$data['jour'])->where('enfant_id',$idE)->get();
		$error = 0;
		$heure = date("H:i:s");
		$jour = date("Y-m-d");
		if ( $data['type'] == 'bus'){
			foreach($days as $day){
				if ( $day->type == 'bus'){
					$error = 1;
				}
			}
			if ( $error == 0 ){
				if ( isset($data['soir']) ){
					if ( $heure > '15:30:00' && $jour == $data['jour']){
						$error = 2;
					}
					else{
						$data['type'] = 'bus_soir';
						$creation = Exceptionnelle::create($data);
					}
				}
				if ( isset($data['matin']) ){
					if ( $jour == $data['jour'] && $heure > '07:15:00'){
						$error = 3;
					}
					else{
						$data['type'] = 'bus_matin';
						$creation = Exceptionnelle::create($data);
					}
				}
			}
			else{ return view('calendrier.exceptionnelle',compact('idE','error'));}
			
		}
		else{
			foreach($days as $day){
				if ( $day->type == 'cantine'){
					$error = 1;
				}
			}
			if ( $error == 0 ){
				
				$creation = Exceptionnelle::create($data);
			}
			else{
				 return view('calendrier.exceptionnelle',compact('idE','error'));
			}
		} 

		 return redirect(route('inscription.show',$idE,$request));
	}

	public function rechercheParent($id,Request $request)
	{
		
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$event = Exceptionnelle::findOrFail($id);
		$idE = $event->enfant_id;
		$event->delete();
		return redirect(route('inscription.show',$idE));
	}

}
