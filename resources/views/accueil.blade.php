@extends('template')

@section('titre')
  Bienvenue sur le site du SIVUSEM
@stop

@section('contenu')
	<p id = "text-accueil"> Bienvenue sur le site du SIVUSEM </p>
 	<a href="auth/login" class="btn btn-primary btn-lg col-lg-4 col-lg-offset-4">SE CONNECTER</a>

@stop