@extends('template')

@section('titre')
	 Commandes
@stop

@section('contenu')
	<div class="jumbotron">
		<div class="btn-group-vertical col-lg-offset-3">
				<div class="btn-group btn-block">
					<!-- <a href="{{url('commande')}}" class="btn btn-default"> Commander pour la semaine prochaine </a> -->
					<a href="{{url('calcul')}}" class="btn btn-default"> Facturation </a>
				</div>
		</div>
	</div>
@stop