@extends('template')

@section('titre')
	 Modifier les informations de {{ $personne->nom. ' '.$personne->prenom }} 

@stop
@section('contenu')
<div class="jumbotron">
	{!! Form::open(['method' => 'PUT', 'url' => route('personnes.update',$personne), 'class' => 'form-horizontal']) !!}
	<fieldset>
		<legend>{{$personne->nom}} {{$personne->prenom }}</legend>
		<div class="form-group {!! $errors->has('nom') ? 'has-error' : '' !!} " >
			<label for="nom" class="col-lg-2 control-label">Nom</label>
			<div class="col-lg-5">
				{!! Form::text('nom', $personne->nom,['class' => 'form-control', 'placeholder' => 'Nom', 'id' => 'nom']) !!}
				{!! $errors->first('nom', '<small class="help-block"> :message </small>') !!}
			</div>
		</div>
		<div class="form-group {!! $errors->has('prenom') ? 'has-error' : '' !!} " >
			<label for="prenom" class="col-lg-2 control-label">Prenom</label>
			<div class="col-lg-5">
				{!! Form::text('prenom', $personne->prenom,['class' => 'form-control', 'placeholder' => 'Prenom', 'id' => 'prenom']) !!}
				{!! $errors->first('prenom', '<small class="help-block"> :message </small>') !!}
			</div>
		</div>
		<div class="form-group {!! $errors->has('adresse') ? 'has-error' : '' !!} " >
			<label for="adresse" class="col-lg-2 control-label">Adresse</label>
			<div class="col-lg-5">
				{!! Form::text('adresse', $personne->adresse,['class' => 'form-control', 'placeholder' => 'adresse', 'id' => 'adresse']) !!}
				{!! $errors->first('adresse', '<small class="help-block"> :message </small>') !!}
			</div>
		</div>
		<div class="form-group {!! $errors->has('cp') ? 'has-error' : '' !!} " >
			<label for="cp" class="col-lg-2 control-label">Code Postal</label>
			<div class="col-lg-5">
				{!! Form::text('cp', $personne->cp,['class' => 'form-control', 'placeholder' => 'ex:31570', 'id' => 'cp']) !!}
				{!! $errors->first('cp', '<small class="help-block"> :message </small>') !!}
			</div>
		</div>
		<div class="form-group {!! $errors->has('ville') ? 'has-error' : '' !!} " >
			<label for="ville" class="col-lg-2 control-label">Ville</label>
			<div class="col-lg-5">
				{!! Form::text('ville', $personne->ville,['class' => 'form-control', 'placeholder' => 'ville', 'id' => 'ville']) !!}
				{!! $errors->first('ville', '<small class="help-block"> :message </small>') !!}
			</div>
		</div>
		<div class="form-group {!! $errors->has('email') ? 'has-error' : '' !!} " >
			<label for="email" class="col-lg-2 control-label">Email</label>
			<div class="col-lg-5">
				{!! Form::email('email', $personne->email,['class' => 'form-control', 'placeholder' => 'Email', 'id' => 'email']) !!}
				{!! $errors->first('email', '<small class="help-block"> :message </small>') !!}
			</div>
		</div>
		<div class="form-group {!! $errors->has('niveau') ? 'has-error' : '' !!} " >
			<label for="niveau" class="col-lg-2 control-label">Accréditation</label>
			<div class="col-lg-5">
				<select class="form-control" id="niveau" name="niveau" >
					<option value="0" {{ $personne->niveau == 0 ? 'selected' : '' }} >Parent</option>
					<option value="1" {{ $personne->niveau == 1 ? 'selected' : '' }}>Agent</option>
					<option value="2" {{ $personne->niveau == 2 ? 'selected' : '' }}>Administrateur</option>
				</select>
				{!! $errors->first('niveau', '<small class="help-block"> :message </small>') !!}
			</div>
		</div>
		<div class="form-group {!! $errors->has('password') ? 'has-error' : '' !!} " >
			<label for="password" class="col-lg-2 control-label">Mot de Passe</label>
			<div class="col-lg-5">
				{!! Form::password('password', ['class' => 'form-control', 'id' => 'password', 'placeholder' => '']) !!}
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