@extends('template')

@section('titre')
	 Page d'administration  
@stop

@section('contenu')
<div class="jumbotron">
	<h2> Bonjour {{ $user->nom .' '. $user->prenom}} !! </h2>
		<div class="btn-group-vertical col-lg-offset-3">
				<div class="btn-group btn-block">
			      <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
			        Vérifier Inscriptions
			        <span class="caret"></span>
			      </a>
			      <ul class="dropdown-menu">
			          <li><a href="{{ url('affichageCantine') }}">Cantine</a></li>
	                  <li><a href="{{ route('journaliere', 'bus_matin') }}">Bus matin</a></li>
	                  <li><a href="{{ route('journaliere', 'bus_soir') }}">Bus soir</a></li>
	                  <li><a href="{{ route('journaliere', 'garderie') }}">Garderie</a></li>
			       </ul>
			    </div>

			    <div class="btn-group btn-block">
			      <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
			        Liste 
			        <span class="caret"></span>
			      </a>
			      <ul class="dropdown-menu">
			        <li><a href="{{ route('enfants.index') }}">Enfant</a></li>
			        <li><a href="{{ route('personnes.index') }}">Parent</a></li>
			        <li><a href="{{ route('niveau.show') }}">Classe</a></li>
			       </ul>
			    </div>
	@if ( $user->niveau ==  2 )

		    <div class="btn-group btn-block">
		      <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		        Créer
		        <span class="caret"></span>
		      </a>
		      <ul class="dropdown-menu">
		        <li><a href="{{ route('enfants.create') }}">Enfant</a></li>
		        <li><a href="{{ route('personnes.create') }}">Parent</a></li>
		        <li><a href="{{ route('niveau.create') }}">Classe</a></li>
		       </ul>
		    </div>

		    <div class="btn-group" >
			<a href=" {{ route('recherche.index') }}" class="btn btn-default">Rechercher</a>
			</div>
			<div class="btn-group" >
			<a href=" {{ route('envoi') }}" class="btn btn-default">Envoyer message aux parents</a>
			</div>
	
	@endif
			<div class="btn-group" >
					<a href=" {{ route('user.show',$user->id) }}" class="btn btn-default">Voir mes infos personnelles</a>
			</div>
	</div>
</div>

@endsection

