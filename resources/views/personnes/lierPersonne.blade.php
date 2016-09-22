@extends('template')

@section('titre')
	 Lier des Parents
@stop
@section('contenu')

	<div class="jumbotron">
		<table class="table table-stiped table-hover" >
			<thead>
				<tr>
					<th>Nom</th>
					<th>Prenom</th>
				</tr>
			</thead>
			<tbody>
				@forelse($personnes as $personne)
					<tr>
						<td>{{ $personne->nom }} </td>
						<td>{{ $personne->prenom }} </td>
						<td>
							<a class="btn btn-success" href="{{ route('lier', [$personne, $enfant]) }}" >Lier</a>
						</td>
					</tr>
				@empty
				<p>Aucun résultat</p>
				@endforelse
			</tbody>
		</table>
		{!! $personnes->render() !!}
	</div>

@stop