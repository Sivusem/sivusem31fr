@extends('template')

@section('titre')
	 Envoyer un email
@stop

<?php
	if(isset($users['parent']) ){
		$options = ['url' => route('envoiEmailCoche', $users), 'class' => 'form-horizontal'];
	}elseif (isset($users['enfant']) ){
		$options = [ 'url' => route('envoiEmailEnfant', $users) , 'class' => 'form-horizontal'];
	}
	else{
		$options = [ 'url' => route('envoi') , 'class' => 'form-horizontal'];
	}
?>

@section('contenu')
<div class="jumbotron">
	{!! Form::open($options) !!}
	<fieldset>
		<legend>Envoyer un email aux parents </legend>
		<div class="form-group " >
			<label for="sujet" class="col-lg-2 control-label">Sujet</label>
			<div class="col-lg-5">
				{!! Form::text('sujet', null,['class' => 'form-control', 'placeholder' => 'Sujet', 'id' => 'sujet']) !!}
			</div>
		</div>
		<div class="form-group " >
			<label for="nom" class="col-lg-2 control-label">Message</label>
			<div class="col-lg-5">
				{!! Form::textarea('message', null,['class' => 'form-control', 'placeholder' => 'message', 'id' => 'message']) !!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-lg-10 col-lg-offset-2">
				{!! Form::reset('Effacer', ['class' => 'btn btn-default']) !!}
				{!! Form::submit('Envoyer !', ['class' => 'btn btn-primary']) !!}
			</div>
		</div>
	</fieldset>
</div>
@stop
		