<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Reguliere extends Model {

	protected $fillable = ["type", "jours", "enfant_id", 'modificate_by'];

	public function enfant()
	{
		return $this->belongsTo('App\Enfant');
	}

}
