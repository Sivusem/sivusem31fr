<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Ligne extends Model {

	protected $fillable = ['nom', 'communes', 'nb_place'];

	public function arrets()
	{
		return $this->hasMany('App\Arret');
	}

}
