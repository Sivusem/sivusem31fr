<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

// 	niveau 2 -> admin, niveau 1 -> atsem, niveau 0 -> user
define ('ADMIN_LEVEL', 2);
define ('ATSEM_LEVEL', 1);
define ('USER_LEVEL', 0);


class User extends Model implements AuthenticatableContract, CanResetPasswordContract {


	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['nom', 'prenom', 'email', 'password', 'niveau', 'identifiant', 'adresse', 'naissance', 'cp', 'ville'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

	public function enfants()
	{
		return $this->belongsToMany('App\Enfant');
	}

}
