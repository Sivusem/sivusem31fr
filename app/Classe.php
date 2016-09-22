<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Classe extends Model {

	protected $fillable = ["niveau","instituteur", "cycle"];

	public function enfants()
	{
		return $this->hasMany('App\Enfant');
	}

}
