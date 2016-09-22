<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Facturation extends Model {

	protected $fillable = ["semaine", "fixe", "non_fixe", "abscence_signalee", "enfant_id"];

	public function enfant()
	{
		return $this->belongsTo('App\Enfant');
	}

}
