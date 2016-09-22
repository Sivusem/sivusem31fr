@extends('template')

@section('titre')
	 {{ str_replace('_', ' ', $type) }}
@stop

@section('contenu')
	<div class="jumbotron">
		<h3> Inscription pour le :  <?php echo date("m/d/Y",strtotime($jour)); ?> ( Date au format Mois/Jour/Annee )</h3>
		<?php $jour_en_cours = date('Y-m-d',strtotime($jour)); ?>
		{!! Form::open(['method' => 'get', 'url' => route('journaliere', $type)]) !!}
			<div class="container">
		          <div class="row">
		              <div class='col-sm-4'>
		                  <div class="form-group">
		                      <div class='input-group date' id='datetimepicker1'>
		                          <input type="text" name="jour" id="jour" value="<?php echo date("m/d/Y",strtotime($jour));?>" class="form-control datepicker"></input>
		                          <span class="input-group-addon" id ="calendrier">
		                              {!!HTML::image("img/calendrier.png")!!}
		                          </span>
		                      
		                      </div>
		                      
		                  </div>
		              </div>
		          </div>
		          {!! Form::submit('Afficher la liste', ['class' => 'btn btn-primary']) !!}
				<a class="btn btn-info" href="{{ url('exportFiches/'.$type.'/'.$jour_en_cours) }}"> Exporter la fiche du <?php echo date("m/d/Y",strtotime($jour)) ?></a>
            </div>

		{!! Form::close() !!}
		@if ( isset($message) )
					{{ $message }}
		@else
			<table class="table table-stiped table-hover" >

				<thead>
					<tr>
						<th>Nom</th>
						<th>Prenom</th>
						<th>Classe</th>
						<th>Instituteur</th>
						@if ( $type == 'bus_matin' || $type == 'bus_soir')
							<th>Arret</th>
						@endif
						@if ($type != 'garderie')
							<th> Modifié par </th>
						@endif
						
					</tr>
				</thead>
				<tbody>
						@if ($type == 'garderie')
							@foreach($inscrits as $inscrit)
								<tr>
									<td>{{ $inscrit->nom }} </td>
									<td>{{ $inscrit->prenom }} </td>
									<td>@if($inscrit->classe != null)
										{{ $inscrit->classe->niveau }}
										@endif</td>
									<td>@if($inscrit->classe != null)
										{{ $inscrit->classe->instituteur }}
										@endif</td>
								</tr>
							@endforeach
						@elseif ($type == 'cantine')
							@foreach($inscrits as $inscrit)
								@if(($inscrit->enfant) != null)
								<tr>
									<td>{{ $inscrit->enfant->nom }} </td>
									<td>{{ $inscrit->enfant->prenom }} </td>
									<td>@if($inscrit->enfant->classe != null)
										{{ $inscrit->enfant->classe->niveau }}
										@endif</td>
									<td>@if($inscrit->enfant->classe != null)
										{{ $inscrit->enfant->classe->instituteur }}
										@endif</td>
									<td>
										@if ( $inscrit->modificate_by == 0)
												Parent
										@elseif( $inscrit->modificate_by == 1)
												Agent
										@else
												Administrateur 
										@endif
										le {{ date('d-m-Y',strtotime($inscrit->updated_at)) }} à {{ date('H:i:s',strtotime($inscrit->updated_at)) }}
									</td>
								</tr>
								@endif
							@endforeach
						@else
							@foreach($arrets as $arret)
								@foreach ($inscrits as $inscrit)
									@if ( $inscrit->enfant->arret['nom'] == $arret->nom)
										@if(($inscrit->enfant) != null)
											<tr>
												<td>{{ $inscrit->enfant->nom }} </td>
												<td>{{ $inscrit->enfant->prenom }} </td>
												<td>@if($inscrit->enfant->classe != null)
													{{ $inscrit->enfant->classe->niveau }}
													@endif</td>
												<td>@if($inscrit->enfant->classe != null)
													{{ $inscrit->enfant->classe->instituteur }}
													@endif</td>
												<td>{{ $arret->nom }} </td>
												<td>
													@if ( $inscrit->modificate_by == 0)
															Parent
													@elseif( $inscrit->modificate_by == 1)
															Agent
													@else
															Administrateur 
													@endif
													le {{ date('d-m-Y',strtotime($inscrit->updated_at)) }} à {{ date('H:i:s',strtotime($inscrit->updated_at)) }}
												</td>
												<td> 
											</tr>
										@endif
									@endif
								@endforeach
							@endforeach
						@endif
				</tbody>
			</table>
		@endif
	</div>
	<a class="btn btn-primary" href="{{ url('/') }}" >Retour</a>

	<script type="text/javascript">
		$(document).ready(function(){
	   
		$( "#jour" ).datepicker(); 
	    $('#calendrier').click(function(){
	          $("#jour").datepicker("show");  
	          });
	});
	</script>

 </script>
@stop