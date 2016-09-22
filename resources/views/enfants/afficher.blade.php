@extends('template')

@section('titre')
	 Informations de l'élève {{ $enfant->nom. ' '. $enfant->prenom }} 
@stop

@section('contenu')
<div class="jumbotron">
	<h1>{{ $enfant->nom }} {{ $enfant->prenom }}</h1>
	<table class="table table-stiped table-hover">
		<tr>
			<td>Classe </td>
			@if ( isset($enfant->classe->niveau) )
				<td>{{ $enfant->classe->niveau }}</td>
			@else
				<td></td>
			@endif
		</tr>
		<tr>
			<td>Instituteur </td>
			@if ( isset($enfant->classe->instituteur) )
				<td>{{ $enfant->classe->instituteur }}</td>
			@else
				<td></td>
			@endif
		</tr>
		<tr>
			<td>Date de naissance </td>
			@if( date('d/m/Y', strtotime($enfant->naissance)) != '01/01/1970')
				<td>{{ date('d/m/Y', strtotime($enfant->naissance)) }}
			@else
				<td>
			@endif
		</td>
		<tr>
			<td> Peut Rentrer seul </td>
			<td>
				@if($enfant->rentre_seul)
					OUI
				@else
					NON
				@endif
			</td>
		</tr>
		@if(! $enfant->mange_cantine)
			<tr><td> Ne mange pas à la cantine. </td></tr>
		@else
			@if($enfant->exception_porc)
				<tr><td>Ne peut pas manger de porc.</td><td></td></tr>
			@endif
			@if($enfant->exception_viande)
				<tr><td>Ne peut pas manger de viande.</td><td></td></tr>
			@endif
			@if($enfant->exception_autre != '')
				<tr><td>autre exception</td><td>{{$enfant->exception}}</td></tr>
			@endif
		@endif
		<tr><td>{!! Form::open(['url' => route('enfants.destroy', $enfant), 'method' => 'delete', 'display' => 'inline-block']) !!}
			<a class="btn btn-primary" href="{{ route('enfants.index') }}" >Retour</a>
			<a class="btn btn-warning" href="{{ route('enfants.edit', $enfant) }}" >Modifier</a>
			@if( Auth::user()->niveau == 2)<a class="btn btn-success" href="{{ url('rechercheAll/'.$enfant->id.'/0') }}" >Lier un parent</a>@endif
            <div class="btn-group">
		      <a href="#" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		        Modifier inscriptions 
		        <span class="caret"></span>
		      </a>
		      <ul class="dropdown-menu">
		        <li><a href="{{ route('reguliere.edit', $enfant) }}">Hebdomadaire</a></li>
              <li><a href="{{ route('inscription.show', $enfant->id) }}">Exceptionnelle</a></li>
		       </ul>
		    </div>
			@if( Auth::user()->niveau == 2)<button type="submit" class="btn btn-danger" >Supprimer</button>@endif
			{!! Form::close() !!}<td>
		</tr>
	</table>
</div>

@stop