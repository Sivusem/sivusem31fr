@extends('template')

@section('contenu')

<div class="jumbotron">
	{!! Form::open(['method'=>'put', 'class'=>'form-horizontal', 'url' => route('liaison.update',$personne) ]) !!}
	<fieldset>
		<legend>Rechercher un enfant</legend>
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
							</div>
						</div>
					</form>
				</fieldset>
	</div>
	
@endsection