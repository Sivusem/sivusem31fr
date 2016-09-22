@extends('template')

@section('titre')
	Liste des parents 
@stop

@section('contenu')
	{!!Form::open(['url' => route('envoiCoche') , 'method' => 'post']) !!}
	<div class="jumbotron">
		<table class="table table-stiped table-hover" >
			<thead>
				<tr>
					<th><input type="button" name="all" id="all" class="btn btn-info" value="Tout Cocher" ></th>
					<th>Nom</th>
					<th>Prenom</th>
					@if( Auth::user()->niveau == 2)<th><a class="btn btn-success" href="{{ route('personnes.create') }}" >Ajouter Utilisateur</a></th>@endif
				</tr>
			</thead>
			<tbody>
				
				@forelse($personnes as $personne)
					<tr>
						<td>{!! Form::checkbox('parent[]',$personne->id) !!}</td>
						<td>{{ $personne->nom }} </td>
						<td>{{ $personne->prenom }} </td>
						<td>
								<a class="btn btn-primary" href="{{ route('personnes.show', $personne) }}" >Détails</a>
								<a class="btn btn-warning" href="{{ route('personnes.edit', $personne) }}" >Modifier</a>
								@if( Auth::user()->niveau == 2)<a class="btn btn-danger" href="{{ route('personnes.destroy', $personne->id) }}" data-delete="{{ csrf_token() }}" >Supprimer</a>@endif
						
						</td>

					</tr>
				@empty
					<tr>
						<td> Aucune personne trouvée. </td>
					</tr>
				@endforelse
				
			</tbody>
		</table>
		{!! $personnes->render() !!}
	</div>
	<button id="emailCoche" type="submit"  class="btn btn-success" >Envoyer un email aux personnes sélectionnées</button>
    {!! Form::close() !!}

    <script type="text/javascript">
	$(document).ready(function(){
		$('#emailCoche').hide();
		$('form').change(function(){	
  			if ( $('input:checked').length != 0 ){
  				$('#emailCoche').slideDown(400);
  			}
  			else{
  				$('#emailCoche').slideUp(400);
  			}
  		});

  		$('#all').click(function(){
  			if($('#all').val() == 'Tout Cocher'){
  				$('input:checkbox').prop('checked', true);
  				$('#all').val('Tout décocher');
  			}else{
  				$('input:checkbox').prop('checked', false);
  				$('#all').val('Tout Cocher');
  			}
  			$('form').trigger('change');
  		});
  		
	});

   </script>


@stop