@extends('template')

@section('titre')
	Modifier mes inscriptions
@stop

@section('contenu')
	<div class="jumbotron">
		<h2> Modifier les inscriptions de mes enfants </h2>
		<table class="table table-stiped table-hover">
			@foreach( $enfants as $enfant )
				<tr>
					<td> {{ $enfant->nom }} </td>
					<td>{{ $enfant->prenom }}</td>
					<td>{{ $enfant->classe->niveau }}</td>
					<td>
						<a href="{{ route('reguliere.edit', $enfant) }}" class="btn btn-primary" >Inscriptions hebdomadaires </a>
						<a href="{{ route('inscription.show', $enfant->id) }}" class="btn btn-primary" >Inscriptions exceptionnelles </a>
					</td>
				</tr>
			@endforeach
		</table>
	</div>
@stop