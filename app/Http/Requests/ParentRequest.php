<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class ParentRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'nom' => 'required',
			'prenom' => 'required',
			'email' => 'email',
			'cp' => 'required|digits:5|integer|',
			'adresse' => 'required',
			'ville' => 'required'
		];
	}

}