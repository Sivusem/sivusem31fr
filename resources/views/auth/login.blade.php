@extends('template')

@section('titre')
	 Connexion au site  
@stop

@section('contenu')
<div class="jumbotron">
	<form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/login') }}">
	<fieldset>
		<legend>Connexion</legend>
					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Oops!</strong> Erreur d'authentification.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

						<div class="form-group">
							<label class="col-lg-2 control-label">Identifiant</label>
							<div class="col-lg-5">
								<input type="text" class="form-control" name="identifiant" >
							</div>
						</div>

						<div class="form-group">
							<label class="col-lg-2 control-label">Mot de passe</label>
							<div class="col-lg-5">
								<input type="password" class="form-control" name="password">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="remember"> Se souvenir de moi
									</label>
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="col-lg-5 col-lg-offset-2">
								<button type="submit" class="btn btn-primary">Connexion</button>
								<a class="btn btn-default" href="{{ url('/password/email') }}">Mot de passe oublié?</a>
							</div>
						</div>
					</form>
				</fieldset>
	</div>
	
@endsection
