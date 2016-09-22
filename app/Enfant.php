<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Enfant extends Model {

	protected $fillable = ["nom","prenom","sexe", "classe_id", "naissance", "garderie", "mange_cantine", 'niveau_classe', 'responsable_bus',
		"prendre_bus", "arret_id", "rentre_seul", "exception_porc", "exception_viande", "exception_autre"];

	public function users()
	{
		return $this->belongsToMany('App\User');
	}

	public function classe()
	{
		return $this->belongsTo('App\Classe');
	}

	public function regulieres()
	{
		return $this->hasMany('App\Reguliere');
	}

	public function exceptionnelles()
	{
		return $this->hasMany('App\Exceptionnelle');
	}

	public function arret()
	{
		return $this->belongsTo('App\Arret');
	}

	public function facturation()
	{
		return $this->hasMany('App\Facturation');
	}

}
