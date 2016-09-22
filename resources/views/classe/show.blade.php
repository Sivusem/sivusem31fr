@extends('template')

@section('titre')
	{{ $classe->niveau }}
@stop

@section('contenu')
<div class="jumbotron">
	<h1>{{ $classe->niveau }}</h1>
	<table class="table table-stiped table-hover">
		<tr>
			<td>Instituteur : </td><td>{{ $classe->instituteur }}</td>
		
		<td>{!! Form::open(['url' => route('niveau.destroy', $classe), 'method' => 'delete', 'display' => 'inline-block']) !!}
			<a class="btn btn-primary" href="{{ route('niveau.index') }}" >Retour</a>
			<a class="btn btn-warning" href="{{ route('niveau.edit', $classe) }}" >Modifier</a>
			@if( Auth::user()->niveau == 2)<button type="submit" class="btn btn-danger" >Supprimer</button>@endif
			{!! Form::close() !!}</td>
		</tr>
	</table>
	<table class="table table-stiped table-hover">
		<thead>
				<tr>
					<th>Nom</th>
					<th>Prenom</th>
				</tr>
			</thead>
			<tbody>
				@foreach($enfants as $enfant)
					<tr>
						<td>{{ $enfant->nom }}</td>
						<td>{{ $enfant->prenom }}</td>
					</tr>	
				@endforeach
			</tbody>
	</table>
</div>

@stop