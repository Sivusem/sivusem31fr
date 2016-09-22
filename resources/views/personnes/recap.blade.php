@extends('template')

@section('titre')
	Fiche de {{ $personne['nom'].' '.$personne['prenom'] }}
@stop

@section('contenu')
	<div class="jumbotron">
	<h1>{{ $personne['nom'].' '.$personne['prenom'] }}</h1>
	<table class="table table-stiped table-hover">
		<tr>
			<td>Identifiant : </td><td>{{ $personne['identifiant'] }}</td>
		</tr>
		<tr>
			<td>Mot de passe : </td><td>{{ $mdp }}</td>
		</tr>
		<tr >
			<td>Email : </td><td>{{ $personne['email'] }}</td>
		</tr>
		<tr >	
			<td>Adresse : </td><td>{{ $personne['adresse'] }}</td>
		</tr>
		<tr >	
			<td>Code Postal : </td><td>{{ $personne['cp'] }}</td>
		</tr>
		<tr >	
			<td>Ville : </td><td>{{ $personne['ville'] }}</td>
		</tr>
		<tr>
			<td>Accréditation : </td><td>
			<?php
				switch ($personne['niveau']) {
					case 0:
						echo 'Parent';
						break;
					case 1:
						echo 'Agent';
						break;
					default:
						echo 'Administrateur';
						break;
				}
			?></td>
		</tr>
		</tr><tr><td> </td></tr>
		<tr><td>{!! Form::open(['url' => route('personnes.destroy', $personne), 'method' => 'delete', 'display' => 'inline-block']) !!}
			<a class="btn btn-primary" href="{{ route('personnes.index') }}" >Retour</a>
			<a class="btn btn-success" href="{{ url('rechercheAll/'.$personne['id'].'/1') }}" >Lier un enfant</a>
			<a class="btn btn-warning" href="{{ route('personnes.edit', $personne) }}" >Modifier</a>
			@if( Auth::user()->niveau == 2)<button type="submit" class="btn btn-danger" >Supprimer</button>@endif
			{!! Form::close() !!}<td>
		</tr>
	</table>
</div>
@stop