$(document).ready(function(){

	var type = 0;
	$('#creer_Parent').click(function(event){
		event.preventDefault();
		$('.formulaire').fadeOut(500).queue(function(){
			$('.Parent').fadeIn(500);
			$(this).dequeue();
		});
		type = 1;
	});
	$('#creer_Enfant').click(function(event){
		event.preventDefault();
		$('.formulaire').fadeOut(500).queue(function(){
			$('.Enfant').fadeIn(500);
			$(this).dequeue();
		});
		type = 2;
	});
	$('.recherche').click(function(event){
		event.preventDefault();
		if(type == 1){
			$('.Parent').fadeOut(500).queue(function(){
				$('.formulaire').fadeIn(500);
				$(this).dequeue();
			});
		}else{
			$('.Enfant').fadeOut(500).queue(function(){
				$('.formulaire').fadeIn(500);
				$(this).dequeue();
			});
		}
		type = 0;
	});

});