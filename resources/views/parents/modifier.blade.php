@extends('template')

@section('titre')
	 Modifier les informations de {{ $personne->nom.' '.$personne->prenom }} 
@stop
@section('contenu')

<div class="jumbotron">
	{!! Form::open(['method' => 'put', 'url' => route('user.update',$personne), 'class' => 'form-horizontal']) !!}
	<fieldset>
		<legend>{{$personne->nom}} {{$personne->prenom }}</legend>
		<div class="form-group {!! $errors->has('adresse') ? 'has-error' : '' !!} " >
			<label for="adresse" class="col-lg-2 control-label">Adresse</label>
			<div class="col-lg-5">
				{!! Form::text('adresse', $personne->adresse,['class' => 'form-control', 'placeholder' => 'Adresse', 'id' => 'adresse']) !!}
				{!! $errors->first('adresse', '<small class="help-block"> :message </small>') !!}
			</div>
		</div>
		<div class="form-group {!! $errors->has('email') ? 'has-error' : '' !!} " >
			<label for="email" class="col-lg-2 control-label">Email</label>
			<div class="col-lg-5">
				{!! Form::email('email', $personne->email,['class' => 'form-control', 'placeholder' => 'Email', 'id' => 'email']) !!}
				{!! $errors->first('email', '<small class="help-block"> :message </small>') !!}
			</div>
		</div>
		<div class="form-group {!! $errors->has('password') ? 'has-error' : '' !!} " >
			<label for="password" class="col-lg-2 control-label">Mot de passe</label>
			<div class="col-lg-5">
				{!! Form::password('password',['class' => 'form-control', 'id' => 'password']) !!}
				{!! $errors->first('password', '<small class="help-block"> :message </small>') !!}
			</div>
		</div>
		<div class="form-group {!! $errors->has('password_confirmation') ? 'has-error' : '' !!} " >
			<label for="password_confirmation" class="col-lg-2 control-label">Confirmation</label>
			<div class="col-lg-5">
				{!! Form::password('password_confirmation',['class' => 'form-control', 'id' => 'password_confirmation']) !!}
				{!! $errors->first('password_confirmation', '<small class="help-block"> :message </small>') !!}
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