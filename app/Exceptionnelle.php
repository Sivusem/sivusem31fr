<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Exceptionnelle extends Model {

	
	public function enfant(){

		return $this->belongsTo('App\Enfant');
	}
	
	protected $fillable = ['type', 'jour','inscrit','enfant_id', 'modificate_by'];

}
