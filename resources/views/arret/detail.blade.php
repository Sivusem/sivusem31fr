@extends('template')

@section('titre')
	 Informations de l'arret {{ $arret->nom }} 
@stop

@section('contenu')
<div class="jumbotron">
	<h1>{{ $arret->nom }} </h1>
	<table class="table table-stiped table-hover">
		<tr>
			<td>Commune </td><td>
			<td>{{ $arret->commune }}
		</td>
		<tr>
			<td>Numéro de l'arrêt </td><td>
			<td>{{ $arret->numero_arret }}
		</td>
		<tr>
			<td>Ligne associée </td><td>
			<td>{{ $arret->ligne->nom }}
		</td>
		</tr><tr><td> </td></tr>
		<tr><td>{!! Form::open(['url' => route('arret.destroy', $arret), 'method' => 'delete', 'display' => 'inline-block']) !!}
			<a class="btn btn-primary" href="{{ route('arret.index') }}" >Retour</a>
			<a class="btn btn-warning" href="{{ route('arret.edit', $arret) }}" >Modifier</a>
			@if( Auth::user()->niveau == 2)<button type="submit" class="btn btn-danger" >Supprimer</button>@endif
			{!! Form::close() !!}<td>
		</tr>
	</table>
</div>

@stop