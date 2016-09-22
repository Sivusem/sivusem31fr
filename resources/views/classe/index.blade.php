@extends('template')

@section('titre')
	Liste des classes
@stop

@section('contenu')
<div class="jumbotron">
	<table class="table table-stiped table-hover" >
		<thead>
			<tr>
				<th>Niveau</th>
				<th>Instituteur(rice)</th>
				<th>Cycle</th>
				@if( Auth::user()->niveau == 2)<th><a class="btn btn-success" href="{{ route('niveau.create') }}" >Ajouter Classe</a></th>@endif
			</tr>
		</thead>
		<tbody>
			@forelse($classes as $classe)
				<tr>
					<td>{{ $classe->niveau }} </td>
					<td>{{ $classe->instituteur }} </td>
					<td>{{ $classe->cycle }} </td>
					<td>
							<a class="btn btn-primary" href="{{ route('niveau.show', $classe) }}" >Détails</a>
							<a class="btn btn-warning" href="{{ route('niveau.edit', $classe) }}" >Modifier</a>
							@if( Auth::user()->niveau == 2)<a class="btn btn-danger" href="{{ route('niveau.destroy', $classe->id) }}" data-delete="{{ csrf_token() }}" >Supprimer</a>@endif
					
					</td>

				</tr>
			@empty
				<tr>
					<td> Aucune classe trouvée. </td>
				</tr>
			@endforelse
			
		</tbody>
	</table>
	{!! $classes->render() !!}
</div>


@stop