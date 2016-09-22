@extends('template')

@section('titre')
	 Modifications de début d'année
@stop

@section('contenu')
<div class="jumbotron">
	<div class="btn-group-vertical col-lg-offset-4">
				<div class="btn-group btn-block">
			      <a href="{{ url('supprimer') }}" class="btn btn-default" id="export" > Exporter et Supprimer l'année en cours </a>
			      <button class="btn btn-default" data-toggle="modal" data-target="#myModal">
			      	Importer les nouvelles classes
			      </button>
			      <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledly="myModalLabel">
			      	<div class="modal-dialog">
			      		<div class="modal-content">
			      			<div class="modal-header">
			      				<button type="button" class="close" data-dismiss="modal"><span class="glyphicon glyphicon-remove-circle"></span></button>
			      				<h4 class="modal-title" id="myModalLabel"> Sélectionnez un fichier à importer dans la base de données </h4>
			      			</div>
			      			<div class="modal-body">
			      				{!! Form::open([ 'method' => 'post' ,  'url' => url('importer'), 'files' => true ]) !!}
			      				
			      				
			      						{!! Form::file('importer', [ 'class' => 'form-control importer']) !!}
										
									<div class="form-group envoiFichier">
										<div class=" col-lg-offset-7">
											{!! Form::reset('Effacer', ['class' => 'btn btn-default']) !!}
											{!! Form::submit('Envoyer !', ['class' => 'btn btn-primary']) !!}
										</div>
									</div>
								
			      				{!! Form::close() !!}
			      			</div>
			      		</div>
			      	</div>
			      </div>
			    <div hidden class="success">
					Exportation et remise à zéro effectuée avec succès.
			    </div>
			   </div>
	</div>
</div>


@stop

<script type="text/javascript">
	$(document).ready(function(){
		$('a.poplight').on('click', function() {
		var popID = $(this).data('rel'); //Trouver la pop-up correspondante
		var popWidth = $(this).data('width'); //Trouver la largeur

		//Faire apparaitre la pop-up et ajouter le bouton de fermeture
		$('#' + popID).fadeIn().css({ 'width': popWidth}).prepend('<a href="#" class="close"><img src="close_pop.png" class="btn_close" title="Close Window" alt="Close" /></a>');
		
		//Récupération du margin, qui permettra de centrer la fenêtre - on ajuste de 80px en conformité avec le CSS
		var popMargTop = ($('#' + popID).height() + 80) / 2;
		var popMargLeft = ($('#' + popID).width() + 80) / 2;
		
		//Apply Margin to Popup
		$('#' + popID).css({ 
			'margin-top' : -popMargTop,
			'margin-left' : -popMargLeft
		});
		
		//Apparition du fond - .css({'filter' : 'alpha(opacity=80)'}) pour corriger les bogues d'anciennes versions de IE
		$('body').append('<div id="fade"></div>');
		$('#fade').css({'filter' : 'alpha(opacity=80)'}).fadeIn();
		
		return false;
	});
	
	
	//Close Popups and Fade Layer
	$('body').on('click', 'a.close, #fade', function() { //Au clic sur le body...
		$('#fade , .popup_block').fadeOut(function() {
			$('#fade, a.close').remove();  
	}); //...ils disparaissent ensemble
		
		return false;
	});


	});
</script>