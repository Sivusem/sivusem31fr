@extends('template')

@section('titre')
	 Mes inscriptions  
@stop

@section('contenu')
<div class="jumbotron" > 
		<h3>Inscription pour l'enfant  {{ $enfant->nom}} {{ $enfant->prenom }}</h3>	
	
	<table class="table table-stiped table-hover" class="background-color: #f2ca27">
			<thead>
				<tr>
					<th> Type </th>	
					<th> Inscrit/Désinscrit </th>
					<th> Date </th>
				</tr>
			</thead>
			<tbody>
				<tr><td></td></tr>
				<tr>
						<td> Cantine </td> <td></td><td></td><td></td><td></td>
				</tr>
						@forelse ( $cantines as $cantine)
							<tr><td></td>
							 @if ( $cantine->inscrit == 1 )
								<td> Inscrit </td>
							 @else 
								<td> Désinscrit </td>
							 @endif
							 {!! Form::open(['url' => route('inscription.destroy', $cantine->id), 'method' => 'delete', 'display' => 'inline-block']) !!}
							 <td> <?php echo date("d/m/Y",strtotime($cantine->jour)); ?> </td>
							 <td><button type="submit" class="btn btn-danger" > Supprimer <span class="glyphicon glyphicon-remove-circle"></span></button></td>
							 {!! Form::close() !!}
							</tr>
						@empty
							<tr><td></td><td> Aucune exception ajoutée. </td> <td></td><td></td></tr>
						@endforelse
				<tr>
						<td> Bus Matin </td> <td></td><td></td><td></td><td></td>
				</tr>
						@forelse ( $bus_matins as $matin)
							<tr><td></td>
							 @if ( $matin->inscrit == 1 )
								<td> Inscrit </td>
							 @else 
								<td> Désinscrit </td>
							 @endif
							 {!! Form::open(['url' => route('inscription.destroy', $matin->id), 'method' => 'delete', 'display' => 'inline-block']) !!}
							 <td> <?php echo date("d/m/Y",strtotime($matin->jour)); ?> </td>
							 <td><button type="submit" class="btn btn-danger" > Supprimer <span class="glyphicon glyphicon-remove-circle"></span></button></td>
							 {!! Form::close() !!}
							</tr>
						@empty
							<tr><td></td><td>Aucune exception ajoutée. </td> <td></td> <td></td></tr>
						@endforelse
			
				<tr>
						<td> Bus Soir </td> <td></td><td></td><td></td><td></td>
				</tr>
						@forelse ( $bus_soirs as $soir)
							<tr><td></td>
							 @if ( $soir->inscrit == 1 )
								<td> Inscrit </td>
							 @else 
								<td> Désinscrit </td>
							 @endif
							{!! Form::open(['url' => route('inscription.destroy', $soir->id), 'method' => 'delete', 'display' => 'inline-block']) !!}
							 <td> <?php echo date("d/m/Y",strtotime($soir->jour)); ?></td>
							 <td><button type="submit" class="btn btn-danger" > Supprimer <span class="glyphicon glyphicon-remove-circle"></span></button></td>
							 {!! Form::close() !!}
							</tr>
						@empty
							<tr> <td></td><td>Aucune exception ajoutée. </td> <td></td><td></td> </tr>
						@endforelse
			</tbody>
	</table>
	<a  href="{{route('inscription.edit',$enfant) }}" class="btn btn-success" >Modifier mes inscriptions</a>
	<a class="btn btn-primary" href="{{ route('reguliere.index') }}" >Retour</a>
</div>

<script type="text/javascript">
$('[data-method]').append(function(){
	        return "\n"+
	        "<form action='"+$(this).attr('href')+"' method='POST' style='display:none'>\n"+
	        "   <input type='hidden' name='_method' value='"+$(this).attr('data-method')+"'>\n"+
	        "<input type=\"hidden\" name=\"_token\" value=\"{{ csrf_token() }}\">"+
	        "</form>\n"
	   })
	   .removeAttr('href')
	   .attr('style','cursor:pointer;')
	   .attr('onclick','$(this).find("form").submit();');
		});
</script>

@stop