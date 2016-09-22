@extends('template')

@section('titre')
	 Inscription cantine
@stop

@section('contenu')
	<div class="jumbotron">
		<h3> Inscription pour la semaine :  </h3>
		{!! Form::open(['method' => 'get', 'url' => url('affichageCantine')]) !!}
			<div class="form-group {!! $errors->has('semaine') ? 'has-error' : '' !!} " >
				<div class="col-lg-5">
					<select name="semaine" id="semaine" class="form-control">
						<?php
							use App\Library\Feries;
							$numSemaine = 1;
							foreach ( $semaines as $semaine){ 
								$debutSemaine = $semaine['debut']; 
								$jourDebut = date('d',strtotime($debutSemaine));
								$moisDebut = Feries::Mois(date('n',strtotime($debutSemaine)));
								$jourFin = date('d',strtotime($semaine['fin']));
								$moisFin = Feries::Mois(date('n',strtotime($semaine['fin'])));
								if ( $jourSelect == $semaine['debut']){
									echo '<option selected value="'.$semaine['debut'].'">Semaine '.$numSemaine.' : '.$jourDebut.' '.$moisDebut.' au '.$jourFin.' '.$moisFin.'</option>';
								}
								else{
									echo '<option value="'.$semaine['debut'].'">Semaine '.$numSemaine.' : '.$jourDebut.' '.$moisDebut.' au '.$jourFin.' '.$moisFin.'</option>';
								}
								$numSemaine++;
							 } 
						?>
					</select>
					{!! $errors->first('semaine', '<small class="help-block"> :message </small>') !!}
				</div>	
				{!! Form::submit('Afficher la liste', ['class' => 'btn btn-primary']) !!}
			</div>
		{!! Form::close() !!}
		<a class="btn btn-success" href="{{route('commande',$jourSelect)}}"> Editer la commande de la semaine du {{ date('d/m/Y',strtotime($jourSelect)) }}</a>
		<a class="btn btn-success" href="{{url('exportFiches/cantine/'.$jourSelect)}}"> Editer la feuille d'appel de la semaine du {{ date('d/m/Y',strtotime($jourSelect)) }}</a>
		<table class="table table-stiped table-hover" >
			<thead>
				<tr>
					<th>Nom</th>
					<th>Prenom</th>
					<th>Classe</th>
					<th>Instituteur</th>
					<th></th>
					<th style="text-align: center">Lundi</th>
					<th style="text-align: center">Mardi</th>
					<th style="text-align: center">Mercredi</th>
					<th style="text-align: center">Jeudi</th>
					<th style="text-align: center">Vendredi</th>
				</tr>
			</thead>
			<tbody>
				@if ( isset($message) )
					<tr><td> {{ $message }} </td></tr>
				@else
					@foreach($inscrits as $inscrit)
						@if($inscrit['enfant']->enfant != null) 
							<tr>
								<td>{{ $inscrit['enfant']->enfant->nom }} </td>
								<td>{{ $inscrit['enfant']->enfant->prenom }} </td>
								<td>@if($inscrit['enfant']->enfant->classe != null)
									{{ $inscrit['enfant']->enfant->classe->niveau }}
									@endif</td>
								<td>@if($inscrit['enfant']->enfant->classe != null)
									{{ $inscrit['enfant']->enfant->classe->instituteur }}
									@endif</td><td></td>
								@for($i=1; $i<=6; $i++)
									<td style="text-align: center">
										@if(in_array($i, $inscrit['inscription']) == true)
											<span class="glyphicon glyphicon-remove"></span>
										@endif
									</td>
								@endfor
							</tr>
						@endif
					@endforeach
				@endif	
			</tbody>
		</table>
		@if ( !empty($enfant) )
			<a href="{{ url('exportFichier') }}" class="btn btn-info"> Exporter au format Excel </a>
		@endif
	</div>
	<a class="btn btn-primary" href="{{ url('/') }}" >Retour</a> 
@stop