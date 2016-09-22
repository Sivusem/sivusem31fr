@extends('template')

@section('titre')
	Liste des arrêts
@stop

@section('contenu')
	<div class="jumbotron">
		<table class="table table-stiped table-hover" >
			<thead>
				<tr>
					<th>Numéro de l'arrêt</th>
					<th>Nom de l'arrêt</th>
					<th>Commune </th>
					<th> Ligne associée </th>
					@if( Auth::user()->niveau == 2)<th><a class="btn btn-success" href="{{ route('arret.create') }}" >Ajouter un arrêt</a></th>@endif
				</tr>
			</thead>
			<tbody>
				
				@forelse($arrets as $arret)
					<tr>
						<td>{{ $arret->numero_arret }} </td>
						<td>{{ $arret->nom }} </td>
						<td>{{ $arret->commune }} </td>
						@if ( isset($arret->ligne) )
							<td>{{ $arret->ligne->nom }} </td>
						@else
							<td></td>
						@endif
						<td>
								<a class="btn btn-primary" href="{{ route('arret.show', $arret) }}" >Détails</a>
								<a class="btn btn-warning" href="{{ route('arret.edit', $arret) }}" >Modifier</a>
								@if( Auth::user()->niveau == 2)<a class="btn btn-danger" href="{{ route('arret.destroy', $arret->id) }}" data-delete="{{csrf_token()}}" >Supprimer</a>@endif
						</td>
					</tr>
				@empty
					<tr>
						<td> Aucun arrêt créé. </td>
					</tr>
				@endforelse
				
			</tbody>
		</table>
		{!! $arrets->render() !!}
	</div>
	
    {!! Form::close() !!}


@stop