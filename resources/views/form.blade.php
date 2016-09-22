@extends('template')

@section('contenu')
	<div class="col-sm-offset-3 col-sm-6">
		<div class="panel panel-info">
			<div class="panel-heading">Inscription</div>
			<div class="panel-body"> 
				{!! Form::open(['url' => 'inscription/form']) !!}
					<div class="form-group {!! $errors->has('nom') ? 'has-error' : '' !!} " >
						{!! Form::text('nom', null,['class' => 'form-control', 'placeholder' => 'Nom']) !!}
						{!! $errors->first('nom', '<small class="help-block"> :message </small>') !!}
					</div>
					<div class="form-group {{ $errors->has('email') ? 'has-error' : '' }} ">
						{!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'Email']) !!}
						{!! $errors->first('email', '<small class="help-block"> :message </small>') !!}
					</div>
					<div class="form-group {{ $errors->has('password') ? 'has-error' : '' }} ">
						{!! Form::password('password', null,['class' => 'form-control', 'placeholder' => 'Password']) !!}
						{!! $errors->first('password', '<small class="help-block"> :message </small>') !!}
					</div>
					<div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : '' }} ">
						{!! Form::password('password_confirmation', null, ['class' => 'form-control', 'placeholder' => 'Confirmer Password']) !!}
						{!! $errors->first('password_confirmation', '<small class="help-block"> :message </small>') !!}
					</div>
					{!! Form::submit('Envoyer !', ['class' => 'btn btn-info pull-right']) !!}
				{!! Form::close() !!}