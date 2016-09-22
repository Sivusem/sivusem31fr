@extends('template')

@section('titre')
	Facturation
@stop

@section('contenu')
	<div class="jumbotron">
		{!! Form::open(['url' => url('facturation'), 'class' => 'form-horizontal']) !!}
		<fieldset>
		<legend>Facturation</legend>
		<div class="form-group {!! $errors->has('mois') ? 'has-error' : '' !!} " >
			<label for="mois" class="col-lg-2 control-label">Mois de facturation</label>
			<div class="col-lg-5">
				<select name="mois" id="mois" class="form-control">
					<?php
						use App\Library\Feries;
						foreach ( $mois as $month){ 
							$debutMois = Feries::Mois(date('n',strtotime($month["debut"])));
							if ( $debutMois == 'Aout'){
								$debutMois = 'Septembre';
							}
							$finMois = Feries::Mois(date('n',strtotime('+1month',strtotime($month["debut"]))));
							echo '<option value="'.$month['debut'].'">Mois : '.$debutMois.' - '.$finMois.'</option>';
						}
					?>
				</select>
			</div>
		</div>
		<div class="form-group {!! $errors->has('prix_elementaire') ? 'has-error' : '' !!} " >
			<label for="prix_elementaire" class="col-lg-2 control-label">Prix d'un repas élémentaire</label>
			<div class="col-lg-5">
				{!! Form::text('prix_elementaire', null,['class' => 'form-control', 'placeholder' => 'ex : 2.34', 'id' => 'prix_elementaire']) !!}
				{!! $errors->first('prix_elementaire', '<small class="help-block"> :message </small>') !!}
			</div>
		</div>
		<div class="form-group {!! $errors->has('prix_maternelle') ? 'has-error' : '' !!} " >
			<label for="prix_maternelle" class="col-lg-2 control-label">Prix d'un repas maternelle</label>
			<div class="col-lg-5">
				{!! Form::text('prix_maternelle', null,['class' => 'form-control', 'placeholder' => 'ex : 2.34', 'id' => 'prix_maternelle']) !!}
				{!! $errors->first('prix_maternelle', '<small class="help-block"> :message </small>') !!}
			</div>
		</div>
		</fieldset>
		{!! Form::submit('Envoyer !',['class' => "btn btn-primary"]) !!}
		{!!Form::close()!!}
	</div>
@stop