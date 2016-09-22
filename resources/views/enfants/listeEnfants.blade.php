@extends('template')

@section('titre')
	Liste des enfants 
@stop

@section('contenu')
	{!!Form::open(['url' => route('envoiCoche') , 'method' => 'post' ]) !!}
	<div class="jumbotron">
		<div id="erreur" class="alert alert-warning" hidden>
   				 Veuillez cocher au moins une personne. 
		</div>
		<table class="table table-stiped table-hover" >
			<thead>
				<tr>
					<th><input type="button" name="all" id="all" class="btn btn-info" value="Tout Cocher" ></th>
					<th>Nom</th>
					<th>Prenom</th>
					<th> @if (!empty($enfant->classe) )
						<a class="btn btn-link" href="{{ url('enfants/classe/tri') }}"> Classe </a>
						@else
						  Classe
						@endif
					</th>
<!-- 					@if( Auth::user()->niveau == 2)<th><a class="btn btn-success" href="{{ route('enfants.create',$enfants) }}" >Ajouter Enfants</a></th>@endif -->
<!-- Modif Fred : 21/08/16 - Ajout de boutons de modif cantine pour la selection -->
					@if( Auth::user()->niveau == 2)
						<th><a class="btn btn-success" href="{{ route('enfants.create',$enfants) }}" >Ajouter Enfants</a></th>
						<th><a class="btn btn-success" href="{{ route('enfants.create',$enfants) }}" >Modif Cantine</a></th>@endif
				</tr>
			</thead>
			<tbody>
				@foreach($enfants as $enfant)
					<tr>
						<td>{!! Form::checkbox('enfant[]',$enfant->id,null,['class' => 'enfantCoche']) !!}</td>
						<td>{{ $enfant->nom }} </td>
						<td>{{ $enfant->prenom }} </td>
						@if ($enfant->classe != null)
							<td><a class="btn btn-info" href="{{ action('EnfantsController@indexByClass', $enfant->classe) }}" >{{ $enfant->classe->niveau }} </a></td>
						@else
							<td></td>
						@endif
						<td>
								<a class="btn btn-primary" href="{{ route('enfants.show', $enfant) }}" >Détails</a>
								<a class="btn btn-warning" href="{{ route('enfants.edit', $enfant) }}" >Modifier</a>
								@if( Auth::user()->niveau == 2)<a class="btn btn-danger" href="{{ route('enfants.destroy', $enfant->id) }}" data-delete="{{ csrf_token() }}" >Supprimer</a>@endif
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
		{!! $enfants->render() !!}
	</div>
	<button type="submit"  id="emailCoche" class="btn btn-success" >Envoyer un email aux personnes sélectionnées</button>
	{!! Form::close() !!}

	 <script type="text/javascript">
	$(document).ready(function(){
		$('#emailCoche').hide();
  		$('#all').click(function(){
  			if($('#all').val() == 'Tout Cocher'){
  				$('input:checkbox').prop('checked', true);
  				$('#all').val('Tout décocher');
  			}else{
  				$('input:checkbox').prop('checked', false);
  				$('#all').val('Tout Cocher');
  			}
  		});
  		$('form').change(function(){
  			var nb_check = $('input:checked').length;
  			if ( nb_check != 0 ){
  				$('#emailCoche').slideDown(400);
  			}
  			else{
  				$('#emailCoche').slideUp(400);
  			}
  		});
	});

   </script>

@stop