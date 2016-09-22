<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Arret extends Model {

	protected $fillable = ['nom', 'commune', 'numero_arret', 'ligne_id'];

	public function ligne()
	{
		return $this->belongsTo('App\Ligne');
	}

	public function enfants()
	{
		return $this->hasMany('App\Enfant');
	}

}
