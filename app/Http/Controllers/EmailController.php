<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\User;
use App\Enfant;

class EmailController extends Controller {


	public function __construct()
	{
		$this->middleware('adsem');
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function postEmail(Request $request)
	{
		$data = $request->all();
		$emails = User::lists('nom','email');
		 Mail::send('emails.contact', compact('data'), function($message) use ($emails)
		 {
			$message->to($emails);
		}); 

		return view('emails.ok');
	}

	public function postEmailCoche(Request $request)
	{
		$data = $request->all();
		$users = $data['parent'];
		$emails = [];
		foreach ( $users as $user => $id){
			$tmp = User::findOrFail($id);
			$emails[$tmp->email] = $tmp->nom;
		}
		Mail::send('emails.contact', compact('data'), function($message) use ($emails)
		 {
			$message->to($emails);
		});

		return view('emails.ok');
	}

	public function postEmailEnfant (Request $request)
	{
		$data = $request->all();
		$enfants = $data['enfant'];
		foreach ( $enfants as $enfant => $id)
		{
			$tmp = Enfant::findOrFail($id);
			$users = $tmp->users()->get();
			foreach ($users as $user) {
				$emails[$user->email] = $user->nom;
			}
		}
		if ( $emails){
		Mail::send('emails.contact', compact('data'), function($message) use ($emails)
		 {
			$message->to($emails);
		});
			return view('emails.ok');
		}
		else{
			return view('emails.errors');
		}
	}

	public function postCoche(Request $request)
	{
		$users = $request->all();
		// dd($var);
		return view('admin.envoyerMessage',compact('users'));
	}

	public function getEmail()
	{
		return view('admin.envoyerMessage');
	}
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
