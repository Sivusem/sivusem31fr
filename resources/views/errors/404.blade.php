@extends('template')

@section('titre')
	Erreur 404 !
@stop

@section('contenu')
	<div class="jumbotron">
		<h1>Erreur 404 !</h1>
		<p>Cette page n'existe pas !<p>
		<a href="javascript:history.back()" class="btn btn-primary" >Retour</a>
		<a href="{{ url('/') }}" class="btn btn-default">Accueil</a> 
	</div>
@stop