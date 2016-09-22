@extends('template')

@section('titre')
	 Lier des enfants 
@stop
@section('contenu')

	<div class="jumbotron">
		<table class="table table-stiped table-hover" >
			<thead>
				<tr>
					<th>Nom</th>
					<th>Prenom</th>
					<th>Classe<th>
				</tr>
			</thead>
			<tbody>
				@forelse($enfants as $enfant)
					<tr>
						<td>{{ $enfant->nom }} </td>
						<td>{{ $enfant->prenom }} </td>
						<td><a class="btn btn-info" href="" >{{ $enfant->classe->niveau }} </a></td>
						<td>
							<a class="btn btn-success" href="{{ route('lier', [$personne, $enfant]) }}" >Lier</a>
						</td>
					</tr>
				@empty
				<p>Aucun résultat</p>
				@endforelse
			</tbody>
		</table>
		{!! $enfants->render() !!}
	</div>

@stop