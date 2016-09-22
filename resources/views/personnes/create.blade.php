@extends('template')

@section('titre')
	 Créer un parent  
@stop

@section('contenu')
<div class="jumbotron">
	{!! Form::open(['url' => route('personnes.store'), 'class' => 'form-horizontal']) !!}
	<fieldset>
		<legend>Création d'un Parent</legend>
		<div class="form-group {!! $errors->has('nom') ? 'has-error' : '' !!} " >
			<label for="nom" class="col-lg-2 control-label">Nom</label>
			<div class="col-lg-5">
				{!! Form::text('nom', null,['class' => 'form-control', 'placeholder' => 'Nom', 'id' => 'nom']) !!}
				{!! $errors->first('nom', '<small class="help-block"> :message </small>') !!}
			</div>
		</div>
		<div class="form-group {!! $errors->has('prenom') ? 'has-error' : '' !!} " >
			<label for="prenom" class="col-lg-2 control-label">Prenom</label>
			<div class="col-lg-5">
				{!! Form::text('prenom', null,['class' => 'form-control', 'placeholder' => 'Prenom', 'id' => 'prenom']) !!}
				{!! $errors->first('prenom', '<small class="help-block"> :message </small>') !!}
			</div>
		</div>
		<div class="form-group {!! $errors->has('adresse') ? 'has-error' : '' !!} " >
			<label for="adresse" class="col-lg-2 control-label">Adresse</label>
			<div class="col-lg-5">
				{!! Form::text('adresse', null,['class' => 'form-control', 'placeholder' => 'adresse', 'id' => 'adresse']) !!}
				{!! $errors->first('adresse', '<small class="help-block"> :message </small>') !!}
			</div>
		</div>
		<div class="form-group {!! $errors->has('cp') ? 'has-error' : '' !!} " >
			<label for="cp" class="col-lg-2 control-label">Code Postal</label>
			<div class="col-lg-5">
				{!! Form::text('cp', null,['class' => 'form-control', 'placeholder' => 'ex:31570', 'id' => 'cp']) !!}
				{!! $errors->first('cp', '<small class="help-block"> :message </small>') !!}
			</div>
		</div>
		<div class="form-group {!! $errors->has('ville') ? 'has-error' : '' !!} " >
			<label for="ville" class="col-lg-2 control-label">Ville</label>
			<div class="col-lg-5">
				{!! Form::text('ville', null,['class' => 'form-control', 'placeholder' => 'ville', 'id' => 'ville']) !!}
				{!! $errors->first('ville', '<small class="help-block"> :message </small>') !!}
			</div>
		</div>
		
		<div class="form-group {!! $errors->has('email') ? 'has-error' : '' !!} " >
			<label for="email" class="col-lg-2 control-label">Email</label>
			<div class="col-lg-5">
				{!! Form::email('email', null,['class' => 'form-control', 'placeholder' => 'Email', 'id' => 'email']) !!}
				{!! $errors->first('email', '<small class="help-block"> :message </small>') !!}
			</div>
		</div>
		<div class="form-group {!! $errors->has('niveau') ? 'has-error' : '' !!} " >
			<label for="niveau" class="col-lg-2 control-label">Accréditation</label>
			<div class="col-lg-5">
				<select class="form-control" id="niveau" name="niveau" >
					<option value="0" >Parent</option>
					<option value="1" >Agent</option>
					<option value="2" >Administrateur</option>
				</select>
				{!! $errors->first('niveau', '<small class="help-block"> :message </small>') !!}
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