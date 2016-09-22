<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
/***************************************************************/

Route::get('/', 'WelcomeController@index');
Route::get('home','HomeController@index');

/*****************************************************************/

Route::resource('personnes', 'PersonnesController');
Route::resource('enfants', 'EnfantsController');

/***************************************************************/

Route::get('enfants/classe/tri', 'EnfantsController@indexAllClass');
Route::get('enfants/classe/{class}', 'EnfantsController@indexByClass')->where('classe','[0-9]+');

/***************************************************************/

Route::get('auth/register', function(){
	abort(404);
});

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController'
]);

/***************************************************************/

Route::get('envoi', ['uses' => 'EmailController@getEmail', 'as' => 'envoi']);
Route::post('envoi', ['uses' => 'EmailController@postEmail', 'as' => 'envoi']);
Route::post('envoiCoche', ['uses' => 'EmailController@postCoche', 'as' => 'envoiCoche']);
Route::post('envoiEmailCoche', ['uses' => 'EmailController@postEmailCoche', 'as' => 'envoiEmailCoche']);
Route::post('envoiEmailEnfant', ['uses' => 'EmailController@postEmailEnfant', 'as' => 'envoiEmailEnfant']);

/***************************************************************/

Route::resource('user', 'UserController', ['only' => ['show', 'update', 'edit']]);

/***************************************************************/

Route::post('lierParent/{id}', [ 'uses' => 'LiaisonController@lier_creer_parent', 'as' => 'lierParent' ])->where('id','[0-9]+');
Route::post('lierEnfant/{id}', [ 'uses' => 'LiaisonController@lier_creer_enfant', 'as' => 'lierEnfant' ])->where('id','[0-9]+');

/***************************************************************/

Route::get('rechercheAll/{id}/{type}',[ 'as' => 'lierAll' , 'uses' => 'LiaisonController@afficherAll'])->where('id','[0-9]+')->where('type','[0-1]');
Route::resource('recherche','RechercheController', ['only' => ['index', 'store']]);

/***************************************************************/

Route::post('parent_a_lier/{id}', ['as' => 'parent_a_lier', 'uses' => 'LiaisonController@rechercheParent'])->where('id','[0-9]+');
Route::get('lier/{parent}/{enfant}',['as' => 'lier', 'uses' => 'LiaisonController@lier'])->where('parent','[0-9]+')->where('enfant','[0-9]+');
Route::resource('liaison', 'LiaisonController', ['only' => ['update']]);

/***************************************************************/

Route::resource('niveau', 'ClasseController');

/***************************************************************/

Route::resource('reguliere', 'ReguliereController', ['only' => ['index', 'edit', 'update']]);
Route::resource('inscription', 'ExceptionnelleController', ['only' => ['show', 'edit', 'update', 'destroy']]);
Route::post('testInscription', 'ReguliereController@testInscription');

/****************************************************************/

Route::resource('affichage','AffichageController');
Route::get('affichageCantine', 'AffichageController@cantine');
Route::get('journalier/{type}', ['as' => 'journaliere' , 'uses' => 'AffichageController@journalier']);

/****************************************************************/

/*Route::resource('fichier', 'FichierController');
Route::get('exportFichier/{type}', ['as' => 'exportFichier' , 'uses' => 'FichierController@exporter']);*/

/****************************************************************/

//Route::resource('creation', 'CreationController');
Route::get('afficheCreation', 'CreationController@affichageCreation');
Route::get('supprimer','CreationController@supprimerBase');
Route::post('importer','CreationController@importerFichiers');

/****************************************************************/

Route::resource('commandeExport','ExcelController', ['only' => ['index']]);
Route::get('commande/{date}',['uses' => 'ExcelController@commande', 'as' => 'commande']);
Route::get('exportFiches/{type}/{jour}',[ 'uses' => 'ExcelController@exportFiches', 'as' => 'exportFiches']);
Route::post('facturation', ['uses' => 'ExcelController@facturation', 'as' => 'facturation']);
Route::get('calcul','ExcelController@calcul_mois');

/****************************************************************/

Route::resource('ligne','LigneController');
Route::resource('arret','ArretController');

/****************************************************************/

/*Route::get('createFamille','FamilleController@creerFamille');
Route::get('redirige','FamilleController@redirigeCreer')->name('lenomdelaroute');*/

