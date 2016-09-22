@extends('template')

@section('titre')
	 Créer une ligne
@stop

<?php
	if($ligne->id){
		$options = ['method' => 'put', 'url' => action('LigneController@update', $ligne), 'class' => 'form-horizontal'];
	}else{
		$options = ['url' => action('LigneController@store'), 'class' => 'form-horizontal'];
	}
?>

@section('contenu')
<div class="jumbotron">
	{!! Form::model($ligne, $options) !!}
	<fieldset>
		<legend>{!! $ligne->id ? $ligne->nom  : 'Creer une Ligne' !!}</legend>
		<div class="form-group {!! $errors->has('nom') ? 'has-error' : '' !!} " >
			<label for="nom" class="col-lg-2 control-label">Nom</label>
			<div class="col-lg-5">
				{!! Form::text('nom', null,['class' => 'form-control', 'id' => 'nom']) !!}
				{!! $errors->first('nom', '<small class="help-block"> :message </small>') !!}
			</div>
		</div>
		<div class="form-group {!! $errors->has('communes') ? 'has-error' : '' !!} " >
			<label for="communes" class="col-lg-2 control-label">Communes traversées</label>
			<div class="col-lg-5">
				{!! Form::text('communes', null,['class' => 'form-control', 'id' => 'communes']) !!}
				{!! $errors->first('communes', '<small class="help-block"> :message </small>') !!}
			</div>
		</div>
		<div class="form-group {!! $errors->has('nb_place') ? 'has-error' : '' !!} " >
			<label for="nb_place" class="col-lg-2 control-label">Nombre de places</label>
			<div class="col-lg-5">
				{!! Form::number('nb_place', null,['class' => 'form-control', 'id' => 'nb_place']) !!}
				{!! $errors->first('nb_place', '<small class="help-block"> :message </small>') !!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-lg-10 col-lg-offset-2">
				{!! Form::reset('Effacer', ['class' => 'btn btn-default']) !!}
				{!! Form::submit('Envoyer !', ['class' => 'btn btn-primary']) !!}
			</div>
		</div>
	</fieldset>
	{!! Form::close() !!}
</div>

@stop