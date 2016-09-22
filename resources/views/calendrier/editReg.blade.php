@extends('template')

@section('titre')
	Modifier mes inscriptions hebdomadaires
@stop

@section('contenu')
	<div class="jumbotron">
		<h3> Inscription pour l'enfant {{ $enfant->nom }}  {{$enfant->prenom}} </h3>
		{!! Form::open(['url' => route('reguliere.update', $idE), 'method' => 'put' ]) !!}
		<table class="table table-stiped table-hover">
			<thead> <tr><th>Inscriptions hebdomadaires</th></tr> </thead>
			<tbody>
			<tr><td></td><td>Lundi</td><td>Mardi</td><td>Mercredi</td><td>Jeudi</td><td>Vendredi</td></tr>
			@foreach($reg as $r)
			<tr>
				<td>{{ ucfirst(str_replace("_", ' ', $r->type)) }}</td>
				@for ($i=1; $i<=5; $i++)
					<td> 
						{!! Form::checkbox($r->type.'['.$i.']', $i, in_array($i, $jours[$r->type])) !!}
					</td>
				@endfor
			</tr>
			@endforeach
		</tbody>
			<tr><td></td><td></td><td></td><td></td><td></td><td>{!! Form::submit('Valider', ['class' => 'btn btn-primary']); !!}<td></tr>
			{!! $message == '' ? '' : '<tr><td class="success" >'.$message.'</td></tr>' !!}
		</table>
		<a class="btn btn-primary" href="{{ route('reguliere.index') }}" >Retour</a>
		{!! Form::close() !!}
	</div>

@stop