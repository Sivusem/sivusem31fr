@extends('template')

@section('titre')
	 Informations sur la {{ $ligne->nom }} 
@stop

@section('contenu')
<div class="jumbotron">
	<h1>{{ $ligne->nom }}</h1>
	<table class="table table-stiped table-hover">
		<tr>
			<td>Communes </td>
			<td>{{ $ligne->communes }}</td>
		</tr>
		<tr>
			<td>Nombre de places</td>
			<td>{{ $ligne->nb_place }}</td>
		</tr>
		<tr><td>{!! Form::open(['url' => route('ligne.destroy', $ligne), 'method' => 'delete', 'display' => 'inline-block']) !!}
			<a class="btn btn-primary" href="{{ route('ligne.index') }}" >Retour</a>
			<a class="btn btn-warning" href="{{ route('ligne.edit', $ligne) }}" >Modifier</a>
			@if( Auth::user()->niveau == 2)<button type="submit" class="btn btn-danger" >Supprimer</button>@endif
			{!! Form::close() !!}<td><td></td>
		</tr>
	</table>
	<h2>Liste des arrets</h2>
	<table class="table table-stiped table-hover">
		<tr>
			<td>#</td>
			<td>Nom</td>
			<td>Communes</td>
		</tr>
		@forelse($arrets as $arret)
			<tr>
				<td>{{ $arret->numero_arret }}</td>
				<td>{{ $arret->nom }}</td>
				<td>{{ $arret->commune }}</td>
			</tr>
		@empty
			<tr><td></td><td>Aucuns arrets sur cette ligne</td><td></td></tr>
		@endforelse
	</table>
</div>

@stop