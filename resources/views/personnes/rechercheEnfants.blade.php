@extends('template')

	<?php
		if ( $type == 1 ){
			 $name="Enfant" ;
			 $options = ['method'=>'put', 'class'=>'form-horizontal', 'url' => route('liaison.update',$personne) ];
		}
		else{
			   $name="Parent";
			   $options = ['class'=>'form-horizontal', 'url' => route('parent_a_lier',$personne)];
			}
		
	?>

@section('titre')
	 Rechercher un {!! $name !!}
@stop

@section('contenu')

<div class="jumbotron formulaire">
	{!! Form::open($options) !!}
	<fieldset>
		<legend>  Rechercher un {!! $name !!}	</legend>
						<div class="form-group">
							<label class="col-lg-2 control-label">Nom</label>
							<div class="col-lg-5">
								<input type="text" class="form-control" name="nom" >
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Prenom</label>
							<div class="col-lg-5">
								<input type="prenom" class="form-control" name="prenom">
							</div>
						</div>
						<div class="form-group">
							<div class="col-lg-5 col-lg-offset-2">
								<button type="submit" class="btn btn-primary">Rechercher</button>
								<button class="btn btn-success" id="creer_{!!$name!!}">Créer un {!! $name !!}</a>
							</div>
						</div>
					</form>
	</fieldset>
	{!! Form::close() !!}
</div>
<div hidden class="jumbotron Parent">
	{!! Form::open(['url' => route('lierParent',$personne->id), 'class' => 'form-horizontal']) !!}
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
				<button class="btn btn-default recherche">Retour à la recherche</button>
			</div>
		</div>
	</fieldset>
</div>
{!! Form::close() !!}

<div hidden class="jumbotron Enfant">
	{!! Form::open(['url' => route('lierEnfant',$personne->id), 'class' => 'form-horizontal']) !!}
	<fieldset>
		<legend>Creer un Enfant</legend>
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
		<div class="form-group {!! $errors->has('naissance') ? 'has-error' : '' !!} " >
			<label for="naissance" class="col-lg-2 control-label">Date de naissance</label>
			<div class="col-lg-5">
				<div class="col-lg-3">{!! Form::selectRange('jour', 1, 31, null, ['class' => 'form-control', 'id' => 'jour' ]) !!}</div>
				<div class="col-lg-3">{!! Form::selectRange('mois', 1, 12, null, ['class' => 'form-control']) !!}</div>
				<div class="col-lg-5">{!! Form::selectYear('annee',1900,date('Y'), date('Y'), ['class' => 'form-control']) !!}</div>
			</div>
		</div>
		<?php
			use App\Classe;
			$classes = Classe::lists('niveau','id');
		?>
		<div class="form-group {!! $errors->has('classe_id') ? 'has-error' : '' !!} " >
			<label for="classe_id" class="col-lg-2 control-label">Classe</label>
			<div class="col-lg-5">
				{!! Form::select('classe_id', $classes, null, ['id' => 'classe_id', 'class' => 'form-control']) !!}
				{!! $errors->first('classe', '<small class="help-block"> :message </small>') !!}
			</div>
		</div>
		<div class="form-group {!! $errors->has('sexe') ? 'has-error' : '' !!} " >
			<label for="sexe" class="col-lg-2 control-label">Sexe</label>
			<div class="col-lg-5">
				<select class="form-control" id="sexe" name="sexe" >
					<option value="H" {{ $personne->sexe == 'H' ? 'selected' : '' }} >Garcon</option>
					<option value="F" {{ $personne->sexe == 'F' ? 'selected' : '' }} >Fille</option>
				</select>
				{!! $errors->first('sexe', '<small class="help-block"> :message </small>') !!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-lg-10 col-lg-offset-2">
				{!! Form::reset('Effacer', ['class' => 'btn btn-default']) !!}
				{!! Form::submit('Envoyer !', ['class' => 'btn btn-primary']) !!}
				<button class="btn btn-default recherche">Retour à la recherche</button>
			</div>
		</div>
	</fieldset>
	{!! Form::close() !!}
</div>

<script type="text/javascript" src="{{ url('app.js') }}"></script>
@endsection