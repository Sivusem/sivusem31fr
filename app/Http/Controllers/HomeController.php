<?php namespace App\Http\Controllers;

use Illuminate\Auth\Guard;

class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index(Guard $auth)
	{
		if ($auth->check() ){
			$user = $auth->user();
			if ( $user->niveau == 0 ){
				return view('parents.accueilParents',compact('user')); // Parent
			}
			else if ( $user->niveau ){
				return view('admin.accueilAdmin',compact('user')); // Admin
			}
		}
		return redirect()->guest('auth/login');
	}

}
