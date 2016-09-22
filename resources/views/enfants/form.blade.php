@extends('template')

@section('titre')
	 Créer un enfant  
@stop

<?php
	if($enfant->id){
		$options = ['method' => 'put', 'url' => action('EnfantsController@update', $enfant), 'class' => 'form-horizontal'];
	}else{
		$options = ['url' => action('EnfantsController@store'), 'class' => 'form-horizontal'];

	}
?>

@section('contenu')
<div class="jumbotron">
	{!! Form::model($enfant, $options) !!}
	<fieldset>
		<legend>{!! $enfant->id ? $enfant->nom.' '.$enfant->prenom  : 'Creer un Enfant' !!}</legend>
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
			<div class="col-lg-6" style="margin-left:-15px">
				<div class="col-lg-3">{!! Form::selectRange('jour', 1, 31, date("d",strtotime($enfant->naissance)), ['class' => 'form-control', 'id' => 'jour' ]) !!}</div>
				<div class="col-lg-3">{!! Form::selectRange('mois', 1, 12, date("m",strtotime($enfant->naissance)), ['class' => 'form-control']) !!}</div>
				<div class="col-lg-5">{!! Form::selectYear('annee',1900,date('Y'), date("Y",strtotime($enfant->naissance)) ,['class' => 'form-control']) !!}</div>
			</div>
		</div>

		<div class="form-group {!! $errors->has('classe_id') ? 'has-error' : '' !!} " >
			<label for="classe_id" class="col-lg-2 control-label">Classe</label>
			<div class="col-lg-5">
				{!! Form::select('classe_id', $classes, null, ['id' => 'classe_id', 'class' => 'form-control']) !!}
				{!! $errors->first('classe', '<small class="help-block"> :message </small>') !!}
			</div>
		</div>
		<div class="form-group {!! $errors->has('instituteur') ? 'has-error' : '' !!} " >
			<label for="instituteur" class="col-lg-2 control-label">Instituteur</label>
			<div class="col-lg-5">
				{!! Form::select('instituteur', $instituteur, null, ['id' => 'instituteur', 'class' => 'form-control']) !!}
				{!! $errors->first('instituteur', '<small class="help-block"> :message </small>') !!}
			</div>
		</div>

		<div class="form-group {!! $errors->has('sexe') ? 'has-error' : '' !!} " >
			<label for="sexe" class="col-lg-2 control-label">Sexe</label>
			<div class="col-lg-5">
				<select class="form-control" id="sexe" name="sexe" >
					<option value="H" {{ $enfant->sexe == 'H' ? 'selected' : '' }} >Garcon</option>
					<option value="F" {{ $enfant->sexe == 'F' ? 'selected' : '' }} >Fille</option>
				</select>
				{!! $errors->first('sexe', '<small class="help-block"> :message </small>') !!}
			</div>
		</div>
		<h3 class="col-lg-offset-2">Garderie</h3>
		<div class="form-group {!! $errors->has('garderie') ? 'has-error' : '' !!} " >
			<label for="garderie" class="col-lg-2 control-label">Garderie</label>
			<div class="col-lg-5">
				{!! Form::select('garderie',['NON', 'OUI'], null, [ 'id' => 'garderie', 'class' => 'form-control']) !!}
				{!! $errors->first('garderie', '<small class="help-block"> :message </small>') !!}
			</div>
		</div>
		<h3 class="col-lg-offset-2"> Bus </h3>
		<div class="form-group {!! $errors->has('prendre_bus') ? 'has-error' : '' !!} " >
			<label for="prendre_bus" class="col-lg-2 control-label">Prendre le bus</label>
			<div class="col-lg-5">
				{!! Form::select('prendre_bus',['NON', 'OUI'], null, [ 'id' => 'prendre_bus', 'class' => 'form-control']) !!}
				{!! $errors->first('prendre_bus', '<small class="help-block"> :message </small>') !!}
			</div>
		</div>
		<div class="cacher">
			<div class="form-group {!! $errors->has('rentre_seul') ? 'has-error' : '' !!} " >
				<label for="rentre_seul" class="col-lg-2 control-label">Peut rentrer seul (bus)</label>
				<div class="col-lg-5">
					{!! Form::select('rentre_seul',['NON', 'OUI'], null, ['id' => 'rentre_seul', 'class' => 'form-control']) !!}
					{!! $errors->first('rentre_seul', '<small class="help-block"> :message </small>') !!}
				</div>
			</div>
			<div class="form-group {!! $errors->has('arret_id') ? 'has-error' : '' !!} " >
			<label for="arret_id" class="col-lg-2 control-label">Arrêts</label>
			<div class="col-lg-5">
				{!! Form::select('arret_id', $arrets, null, ['id' => 'arret_id', 'class' => 'form-control']) !!}
				{!! $errors->first('arret_id', '<small class="help-block"> :message </small>') !!}
			</div>
		</div>
		</div>

		<h3 class="col-lg-offset-2">Cantine</h3>
		<div class="form-group {!! $errors->has('mange_cantine') ? 'has-error' : '' !!} " >
			<label for="mange_cantine" class="col-lg-2 control-label">Mange à la cantine</label>
			<div class="col-lg-5">
				{!! Form::select('mange_cantine',['NON', 'OUI'], null, ['id' => 'mange_cantine', 'class' => 'form-control']) !!}
				{!! $errors->first('mange_cantine', '<small class="help-block"> :message </small>') !!}
			</div>
		</div>
		<div class="cacher2">
			<div class="form-group {!! $errors->has('exception_viande') ? 'has-error' : '' !!} " >
				<label for="exception_viande" class="col-lg-2 control-label">Peut manger de la viande</label>
				<div class="col-lg-5">
					{!! Form::select('exception_viande',['OUI', 'NON'], null, ['id' => 'exception_viande', 'class' => 'form-control']) !!}
					{!! $errors->first('exception_viande', '<small class="help-block"> :message </small>') !!}
				</div>
			</div>
			<div class="form-group {!! $errors->has('exception_porc') ? 'has-error' : '' !!} " >
				<label for="exception_porc" class="col-lg-2 control-label">Peut manger du porc</label>
				<div class="col-lg-5">
					{!! Form::select('exception_porc',['OUI', 'NON'], null, ['id' => 'exception_porc', 'class' => 'form-control']) !!}
					{!! $errors->first('exception_porc', '<small class="help-block"> :message </small>') !!}
				</div>
			</div>
			<div class="form-group {!! $errors->has('exception_autre') ? 'has-error' : '' !!} " >
				<label for="exception_autre" class="col-lg-2 control-label">Autres exeptions alimentaires</label>
				<div class="col-lg-5">
					{!! Form::text('exception_autre', null,['class' => 'form-control', 'id' => 'exception_autre', 'placeholder' => 'Soumis a confirmation']) !!}
					{!! $errors->first('exception_autre', '<small class="help-block"> :message </small>') !!}
				</div>
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

<script type="text/javascript">

	$(document).ready(function(){

		if($("#mange_cantine").val() == 1){
			$('.cacher2').show();
		}else{
			$('.cacher2').hide();
		}

		if($('#prendre_bus').val() == 1){
			$('.cacher').show();
		}else{
			$('.cacher').hide();
		}

	  	$('#classe_id').click(function(){
	  		var id = $(this).val();
	  		$('#instituteur').val(id);
	  	});

	  	$('#instituteur').click(function(){
	  		var id = $(this).val();
	  		$('#classe_id').val(id);
	  	});

		$('#prendre_bus').change(function (){
			if ( $(this).val() == 1){
				$('.cacher').slideDown(400);
			}
			else{
				$('.cacher').slideUp(400);
			}
		});

		$('#mange_cantine').change(function(){
			if ( $(this).val() == 1 ){
				$('.cacher2').slideDown(400);
			}
			else{
				$('.cacher2').slideUp(400);
			}
		})
	});
</script>
@stop