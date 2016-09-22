@extends('template')

@section('titre')
	Informations du parent {{ $personne->nom.' '.$personne->prenom }} 

@stop
@section('contenu')
<div class="jumbotron">
	<h1>{{ $personne->nom }} {{ $personne->prenom }}</h1>
	<table class="table table-stiped table-hover">
		<tr>
			<td>Identifiant     </td><td>{{ $personne->identifiant }}</td>
		</tr>
		<tr >
			<td>Email     </td><td>{{ $personne->email }}</td>
		</tr>
		<tr>
			<td>Adresse    </td><td>{{ $personne->adresse }}</td>
		</tr>
		<tr>
			<td></td><td><a class="btn btn-primary" href="javascript:history.go(-1)" >Retour</a>
			<a class="btn btn-warning" href="{{ route('user.edit', $personne) }}" >Modifier</a></td>
		</tr>
	</table>
</div>

@stop