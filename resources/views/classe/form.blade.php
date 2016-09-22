@extends('template')

@section('titre')
	 Créer une classe
@stop

<?php
	if($classe->id){
		$options = ['method' => 'put', 'url' => route('niveau.update', $classe), 'class' => 'form-horizontal'];
	}else{
		$options = ['url' => route('niveau.store'), 'class' => 'form-horizontal'];
	}
?>

@section('contenu')
<div class="jumbotron">
	{!! Form::model($classe, $options) !!}
	<fieldset>
		<legend>{!! $classe->id ? $classe->niveau : 'Creer une Classe' !!}</legend>
		<div class="form-group {!! $errors->has('niveau') ? 'has-error' : '' !!} " >
			<label for="niveau" class="col-lg-3 control-label">Niveau(x) de la classe</label>
			<div class="col-lg-5">
				{!! Form::text('niveau', null,['class' => 'form-control', 'placeholder' => 'Niveau', 'id' => 'niveau']) !!}
				{!! $errors->first('niveau', '<small class="help-block"> :message </small>') !!}
			</div>
		</div>
		<div class="form-group {!! $errors->has('instituteur') ? 'has-error' : '' !!} " >
			<label for="instituteur" class="col-lg-3 control-label">Instituteur(rice)</label>
			<div class="col-lg-5">
				{!! Form::text('instituteur', null,['class' => 'form-control', 'placeholder' => 'Instituteur', 'id' => 'instituteur']) !!}
				{!! $errors->first('instituteur', '<small class="help-block"> :message </small>') !!}
			</div>
		</div>
		<div class="form-group {!! $errors->has('cycle') ? 'has-error' : '' !!} " >
			<label for="cycle" class="col-lg-3 control-label">Cycle</label>
			<div class="col-lg-5">
				{!! Form::select('cycle', ['Maternelle' => 'Maternelle', 'Primaire' => 'Primaire'], null,['class' => 'form-control', 'placeholder' => 'cycle', 'id' => 'cycle']) !!}
				{!! $errors->first('cycle', '<small class="help-block"> :message </small>') !!}
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