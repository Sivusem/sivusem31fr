<?php namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Auth\Guard;
use App\Repositories\UserRepository;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller {

    public function __construct()
	{
		$this->middleware('parents');
	}

	public function show($id, Guard $auth)
	{
		if ( $auth->user()->id == $id){
			$personne = User::findOrFail($id);
			return view('parents.show', compact('personne'));
		}
		$personne = User::findOrFail($auth->user()->id);
		return redirect()->action('UserController@show',$auth->user()->id);
	}

	public function edit($id)
	{
		$personne = User::findOrFail($id);
		return view('parents.modifier',  compact('personne'));
	}

	public function update(UserUpdateRequest $request, $id)
	{
		$personne = User::findOrFail($id);
		$data = $request->all();
		$data['password'] =  bcrypt($data['password']);
		$test = $personne->update($data);
		return redirect(route('personnes.show', $id));
	}

}