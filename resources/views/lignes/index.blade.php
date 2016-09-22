@extends('template')

@section('titre')
	Liste des Lignes de bus
@stop

@section('contenu')
	<div class="jumbotron">
		<table class="table table-stiped table-hover" >
			<thead>
				<tr>
					<th>Nom</th>
					<th>Communes</th>
					<th>Nombre de places</th>
					@if( Auth::user()->niveau == 2)<th><a class="btn btn-success" href="{{ route('ligne.create') }}" >Ajouter une ligne</a></th>@endif
				</tr>
			</thead>
			<tbody>
				
				@forelse($lignes as $ligne)
					<tr>
						<td>{{ $ligne->nom }} </td>
						<td>{{ $ligne->communes }} </td>
						<td>{{ $ligne->nb_place }}</td>
						<td>
								<a class="btn btn-primary" href="{{ route('ligne.show', $ligne) }}" >Détails</a>
								<a class="btn btn-warning" href="{{ route('ligne.edit', $ligne) }}" >Modifier</a>
								@if( Auth::user()->niveau == 2)<a class="btn btn-danger" href="{{ route('ligne.destroy', $ligne->id) }}" data-delete="{{ csrf_token() }}" >Supprimer</a>@endif
						</td>
					</tr>
				@empty
					<tr>
						<td> Aucune ligne trouvée. </td>
					</tr>
				@endforelse
				
			</tbody>
		</table>
	</div>


@stop