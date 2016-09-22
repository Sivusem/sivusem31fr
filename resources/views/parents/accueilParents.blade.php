@extends('template')

@section('titre')
	 Accueil  
@stop

@section('contenu')
<div class="jumbotron" >
	<h2>Bonjour {{ $user->nom.' '.$user->prenom }}</h2>
	<div class="btn-group-vertical col-lg-offset-3">
		<div class="btn-group btn-block">
		 	<a class="btn btn-default dropdown-toggle" href="{{ route('reguliere.index') }}" >
		    	Inscription
		  	</a>
		</div>
		<div class="btn-group" >
			<a href=" {{ route('user.show',$user->id) }}" class="btn btn-default">Voir mes infos personnelles</a>
		</div>
	</div>
</div>

@stop