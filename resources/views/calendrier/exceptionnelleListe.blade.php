@extends('template')

@section('titre')
	 Inscription Exceptionnelle  
@stop

@section('contenu')
<div class="jumbotron" > 
	{!! Form::open(['method' => 'put' , 'url' => route('inscription.update',$idE), 'class' => 'form-horizontal']) !!}
	<fieldset>
		<legend> Modification Exceptionelle </legend>
		<div class="form-group" >
     			 <div class="col-lg-10">
        				<div class="Cantine">
         					 <label>
           						 <input type="radio" name="type" id="cantine" value="cantine" checked>
           							 Cantine
          					</label>
        				</div>
        				<div class="Bus">
          					<label>
           						 <input type="radio" name="type" id="bus" value="bus" >
          							  Bus
          					</label>
        				</div>
        				<div class="cacher col-lg-offset-1" hidden>
          					<label>
           						 <input type="checkbox" name="matin[]" id="matin">
          							  Matin
          					</label>
        				</div>
        				<div class="cacher col-lg-offset-1" hidden>
          					<label>
           						 <input type="checkbox" name="soir[]" id="soir"  >
          							  Soir
          					</label>
        				</div>
     			 </div>
    	</div>
        <?php 
        $var = date("N");
        if ( $var >= 2){
               $debut_cantine = date("Y-m-d",mktime(0,0,0, date("m"),date("d")+15-(date("N")),date("Y")));  
               $var = date("m-d-Y",mktime(0,0,0, date("m"),date("d")+15-(date("N")),date("Y")));  
            }
        else{
              $debut_cantine = date("Y-m-d",mktime(0,0,0, date("m"),date("d")+8-(date("N")),date("Y"))); 
              $var = date('m-d-Y',mktime(0,0,0, date("m"),date("d")+8-(date("N")),date("Y")));
            }
        
        ?>
      <div class="form-group">
    		<label class="col-lg-2 control-label"> Sélectionnez le jour  </label> 
        <div class="container">
          <div class="row">
              <div class='col-sm-4'>
                  <div class="form-group">
                      <div class='input-group date' id='datetimepicker1'>
                          <input type="text" name="jour" id="jour" value="format : Mois/Jour/Annee" class="form-control datepicker" min="{{$var}}" other='{{ date('m-d-Y') }}' ajax="{{ url('testInscription') }}" idE="{{$idE}}" data-token="{{ csrf_token() }}" ></input>
                          <span class="input-group-addon" id ="calendrier">
                              {!!HTML::image("img/calendrier.png")!!}
                          </span>
                      </div>
                  </div>
              </div>
            </div>
        </div>
      </div>
        {!! Form::select('inscrit', [0,1],null, ['id' => 'inscrit', 'hidden']) !!}
      @if ( isset($error)  )
          @if ( $error == 1 )
              <div class="danger"> Vous avez déjà une inscription pour cette date !</div> 
          @elseif ( $error == 2 )
              <div class="danger"> Vous avez dépassé l'horaire pour inscrire votre enfant aujourd'hui ! ( Max : 15h30 ) </div>
          @elseif ( $error == 3)
              <div class="danger"> Vous avez dépassé l'horaire pour inscrire votre enfant le matin ! </div>
          @endif
      @endif
    <div class="col-lg-offset-2 message">
      Attention, vous ne pouvez pas vous inscrire à la cantine avant le {{ date("d/m/Y",strtotime($debut_cantine)) }}.
    </div>
		<div class="form-group">
			<div class="col-lg-10 col-lg-offset-2" id="boutton">
				{!! Form::submit('Envoyer !', ['class' => 'btn btn-primary']) !!}
			</div>
		</div>
    <div id='error' class="col-lg-10 col-lg-offset-2 warning" hidden>Une exception est deja presente ici.</div>
    <div id='week_end' class="col-lg-10 col-lg-offset-2 warning" hidden>Vous ne pouvez pas vous inscrire le weekend. </div>
	  <div id="ferie" class="col-lg-10 col-lg-offset-2 warning" hidden>Vous ne pouvez pas vous inscrire les jours fériés. </div>
    <div id="vacances" class="col-lg-10 col-lg-offset-2 warning" hidden>Vous ne pouvez pas vous inscrire pendant les vacances.</div>
  </fieldset>
	{!! Form::close() !!}
</div>

<script type="text/javascript">
	$(document).ready(function(){
    
     var min = $('#jour').attr('min'); 

       $("#jour").datepicker({
        minDate : new Date(min)
       });
       $('#calendrier').click(function(){
          $("#jour").datepicker("show");  
          });
   
    
    var type = 'cantine';
    var other = $('#jour').attr('other');
    $('#boutton').hide();
		$('.cacher').hide();
		$('#bus').click(function(){
      $('#jour').attr('min', other);
      $('#jour').datepicker("option","minDate", new Date(other));
			$('.cacher').slideDown(400);
      type = 'bus';

      
		});
		$('#cantine').click(function(){
      $('#jour').attr('min', min);
      $('#jour').datepicker("option","minDate", new Date(min));
			$('.cacher').slideUp(400);
      type = 'cantine';
		});

    $('#matin').click(function(){
      $('#soir').attr('checked',false);
      type = 'bus_matin';
    });
    $('#soir').click(function(){
      $('#matin').attr('checked',false);
      type = 'bus_soir';
    });



    $('input[type="text"]').change(function(){
      if(type != 'bus'){
        var date = $(this).val();
        var lien = $(this).attr('ajax');
        var id = $(this).attr('idE');
        $('#error').hide();
        $('#boutton').hide();
        $('#week_end').hide();
        $('#vacances').hide();
        $('#ferie').hide();
        $.post(lien, {'date' : date, 'id': id, 'type': type, _token:$(this).data('token') })
        .done(function(data){
          
          if(data.ok){
            $('#error').hide();
            $('#week_end').hide();
            $('#vacances').hide();
            $('#ferie').hide();
            $('#boutton input').val(data.ok);
            $('#boutton').fadeIn(500);
            if(data.ok == 'Inscription'){
              $('#inscrit').val('1');
            }else{
              $('#inscrit').val('0');
            }
          }else{
            $('#boutton').hide();
            if ( data.response == 'week_end'){
              $('#error').hide();
              $('#ferie').hide();
              $('#vacances').hide();
              $('#week_end').fadeIn(200);
            }
            else if ( data.response == 'vacances'){
              $('#error').hide();
              $('#ferie').hide();
              $('#vacances').fadeIn(200);
              $('#week_end').hide();
            }
            else if ( data.response == 'ferie'){
              $('#error').hide();
              $('#ferie').fadeIn(200);
              $('#vacances').hide();
              $('#week_end').hide();
            }
            else{
              $('#week_end').hide();
              $('#ferie').hide();
              $('#vacances').hide();
              $('#error').fadeIn(200);
            }
            
          }
        });
      }else{
        $('checkbox').css('border','red');
      }
    });

	});
</script>


@stop