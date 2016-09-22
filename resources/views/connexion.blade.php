@extends('template')

@section('contenu')
<div class="jumbotron">
	{!! Form::model('user', ['url' => 'connexion/connexion', 'class' => 'form-horizontal']) !!}
	<fieldset>
		<legend>Connexion</legend>
		<div class="form-group {!! $errors->has('nom') ? 'has-error' : '' !!} " >
			<label for="nom" class="col-lg-2 control-label">Identifiant</label>
			<div class="col-lg-5">
				{!! Form::text('nom', null,['class' => 'form-control', 'placeholder' => 'Identifiant', 'id' => 'nom']) !!}
				{!! $errors->first('nom', '<small class="help-block"> :message </small>') !!}
			</div>
		</div>
		<div class="form-group {!! $errors->has('password') ? 'has-error' : '' !!} ">
			<label for="password" class="col-lg-2 control-label">Mot de passe</label>
			<div class="col-lg-5">
				{!! Form::password('password',['class' => 'form-control', 'placeholder' => 'Password', 'id' => 'nom']) !!}
				{!! $errors->first('password', '<small class="help-block"> :message </small>') !!}
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