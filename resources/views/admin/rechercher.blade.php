@extends('template')

@section('titre')
	 Rechercher un enfant/parent  
@stop

@section('contenu')
<div class="jumbotron">
	{!! Form::open([ 'url' => route('recherche.store') , 'class' => 'form-horizontal']) !!}
	<fieldset>
		<legend>Rechercher </legend>
		<div class="form-group " >
			<label for="nom" class="col-lg-2 control-label">Nom</label>
			<div class="col-lg-5">
				{!! Form::text('nom', null,['class' => 'form-control', 'placeholder' => 'Nom', 'id' => 'nom']) !!}
			</div>
		</div>
		<div class="form-group " >
			<label for="prenom" class="col-lg-2 control-label">Prenom</label>
			<div class="col-lg-5">
				{!! Form::text('prenom', null,['class' => 'form-control', 'placeholder' => 'Prenom', 'id' => 'prenom']) !!}
			</div>
		</div>
		<div class="form-group cacher" >
			<label for="niveau_classe" class="col-lg-2 control-label">Niveau</label>
			<div class="col-lg-5">
				{!! Form::text('niveau_classe', null,['class' => 'form-control', 'placeholder' => 'Niveau', 'id' => 'niveau_classe']) !!}
			</div>
		</div>
		<div class="form-group cacher">
			<label for="instituteur" class="col-lg-2 control-label">Nom de l'instituteur</label>
			<div class="col-lg-5">
				{!! Form::text('instituteur', null,['class' => 'form-control', 'placeholder' => 'Instituteur', 'id' => 'instituteur']) !!}
			</div>
		</div>
		<div class="form-group " >
			<label for="ville" class="col-lg-2 control-label">Ville</label>
			<div class="col-lg-5">
				{!! Form::text('ville', null,['class' => 'form-control', 'placeholder' => 'ville', 'id' => 'ville']) !!}
			</div>
		</div>


		<!-- RAJOUTER LA CLASSE AVEC FONCTION DE VAL -->

		<div class="form-group ">
      		<label class="col-lg-2 control-label">Type</label>
     			 <div class="col-lg-10">
        				<div class="radio">
         					 <label>
           						 <input type="radio" name="type" id="parent" value="parent" >
           							 Parent
          					</label>
        				</div>
        				<div class="radio">
          					<label>
           						 <input type="radio" name="type" id="enfant" value="enfant" checked>
          							  Enfant
          					</label>
        				</div>
     			 </div>
    	</div>

		<div class="form-group ">
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
		$('#parent').click(function (){
			$('.cacher').slideUp(400);
		});
		$('#enfant').click(function (){
			$('.cacher').slideDown(400);
		});
  	});
 </script>
@stop