@extends('template')

@section('titre')
	 Ajouter un arrêt  
@stop

<?php
	if($arret->id){

		$options = ['method' => 'put', 'url' => action('ArretController@update', $arret), 'class' => 'form-horizontal'];
	}else{

		$options = ['url' => action('ArretController@store'), 'class' => 'form-horizontal'];
	}
?>

@section('contenu')
<div class="jumbotron">
	{!! Form::model($arret, $options) !!}
	<fieldset>
		<legend>{!! $arret->id ? $arret->nom  : 'Creer un arret' !!}</legend>
		<div class="form-group {!! $errors->has('numero_arret') ? 'has-error' : '' !!} " >
			<label for="numero_arret" class="col-lg-2 control-label">Numéro de l'arrêt</label>
			<div class="col-lg-5">
				{!! Form::text('numero_arret', null,['class' => 'form-control',  'id' => 'numero_arret']) !!}
				{!! $errors->first('numero_arret', '<small class="help-block"> :message </small>') !!}
			</div>
		</div>
		<div class="form-group {!! $errors->has('nom') ? 'has-error' : '' !!} " >
			<label for="nom" class="col-lg-2 control-label">Nom de l'arrêt</label>
			<div class="col-lg-5">
				{!! Form::text('nom', null,['class' => 'form-control', 'id' => 'nom']) !!}
				{!! $errors->first('nom', '<small class="help-block"> :message </small>') !!}
			</div>
		</div>
		<div class="form-group {!! $errors->has('commune') ? 'has-error' : '' !!} " >
			<label for="commune" class="col-lg-2 control-label">Commune</label>
			<div class="col-lg-5">
				{!! Form::text('commune', null,['class' => 'form-control', 'id' => 'commune']) !!}
				{!! $errors->first('commune', '<small class="help-block"> :message </small>') !!}
			</div>
		</div>
		<div class="form-group {!! $errors->has('ligne_id') ? 'has-error' : '' !!} " >
			<label for="ligne_id" class="col-lg-2 control-label">Ligne associée</label>
			<div class="col-lg-5">
				{!! Form::select('ligne_id', $lignes, null, ['id' => 'ligne_id', 'class' => 'form-control']) !!}
				{!! $errors->first('ligne', '<small class="help-block"> :message </small>') !!}
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