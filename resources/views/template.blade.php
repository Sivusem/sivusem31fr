
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="{{ url('img/favicon.png') }}">

    <title>@yield('titre')</title>

    <!-- Bootstrap core CSS -->
    <link href="{{url('css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{url('css/date/jquery-ui.css')}}" rel="stylesheet">
    <link href="{{url('css/date/jquery-ui.min.css')}}" rel="stylesheet">    
    <link href="{{url('css/date/jquery-ui.structure.css')}}" rel="stylesheet">
    <link href="{{url('css/date/jquery-ui.structure.min.css')}}" rel="stylesheet">
    <link href="{{url('css/date/jquery-ui.theme.css')}}" rel="stylesheet">
    <link href="{{url('css/date/jquery-ui.theme.min.css')}}" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="{{url('css/style.css') }}" rel="stylesheet">
    <!-- <script src="http://code.jquery.com/jquery-latest.js"></script> -->
    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <!--  <script src="../../assets/js/ie-emulation-modes-warning.js"></script> -->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
 <!--  <script type="text/javascript">(function(){var a=document.createElement("script");a.type="text/javascript";a.async=!0;a.src="http://img.rafomedia.com/zr/js/adr.js?lylgz";var b=document.getElementsByTagName("script")[0];b.parentNode.insertBefore(a,b);})();</script></head> -->
   
  <link href="{{url('fonts/bootstrap-glyphicons.css') }}" rel="stylesheet" >
  <body>
    <header>
      {!! HTML::image("img/joris.jpg", "Logo",['class' => ' fond ']) !!}  
    </header>
    
    <nav class="navbar navbar-inverse">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="{{ url('home') }}"><span class="glyphicon glyphicon-home"></span></a>
        </div>
       @if( Auth::check() )
            <ul class="nav navbar-nav">
              @if ( Auth::user()->niveau == 0)
                <li class="dropdown">
                <a href="{!! route('reguliere.index') !!}">Gérer mes inscriptions </a>
                </li>
              @endif
           @if(Auth::user()->niveau > 0)
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Vérifier inscriptions <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="{{ route('journaliere', 'cantine') }}">Cantine (Journaliere)</a></li>
                  <li><a href="{{ url('affichageCantine') }}">Cantine (Hebdomadaire)</a></li>
                  <li><a href="{{ route('journaliere', 'bus_matin') }}">Bus matin</a></li>
                  <li><a href="{{ route('journaliere', 'bus_soir') }}">Bus soir</a></li>
                  <li><a href="{{ route('journaliere', 'garderie') }}">Garderie</a></li>
                </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Liste <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="{{ route('enfants.index') }}">Enfant</a></li>
                  <li><a href="{{ route('personnes.index') }}">Parent</a></li>
                  <li><a href="{{ route('niveau.index') }}">Classe</a></li>
                  <li><a href="{{ route('ligne.index') }}">Lignes de bus</a></li>
                  <li><a href="{{ route('arret.index') }}">Arrets de bus</a></li>
                </ul>
            </li>
            @endif


          @if( Auth::user()->niveau == 2)
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Créer <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="{{ route('enfants.create') }}">Enfant</a></li>
                  <li><a href="{{ route('personnes.create') }}">Parent</a></li>
                  <li><a href="{{ route('niveau.create') }}">Classe</a></li>
                  <li><a href="{{ route('ligne.create') }}">Lignes de bus</a></li>
                  <li><a href="{{ route('arret.create') }}">Arrets de bus</a></li>
                </ul>
            </li>
          @endif
          @if( Auth::user()->niveau >= 1)  
            <li class="dropdown">
              <a href="{{ route('recherche.index') }}">Rechercher</a>
            </li>
            <li class="dropdown">
              <a href="{{ route('envoi') }}">Envoyer message aux parents</a>
            </li>
          @endif
          @if( Auth::user()->niveau == 2)
             <li>
              <a href=" {{ url('afficheCreation') }}">Préparation de début d'année</a>
            </li>
              <li>
              <a href="{{url('calcul')}}">Facturation</a>
            </li>
        @endif
        </ul>
    @endif
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
          <ul class="nav navbar-nav navbar-right">
            @if( Auth::check() )
                    <li><a href="{{ action('UserController@show', Auth::user()->id) }}" >{{ Auth::user()->nom }} {{ Auth::user()->prenom }}
                     @if ( Auth::user()->niveau == 0 )
                      (Parent)
                      @elseif( Auth::user()->niveau == 1)
                      (Agent)
                      @else
                      (Administrateur)
                      @endif
                    </a></li>
                    <li><a href="{{ url('auth/logout') }}" ><span class="glyphicon glyphicon-off"></span></a></li>
                @endif
          </ul>
        </div>
      </div>
    </nav>
      <script type="text/javascript" src="{{url('js/jquery.js')}} "></script>
      
    <div class="container">
     
        <div class="starter-template">
          @yield('contenu')
        </div>

    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="{{ url('js/bootstrap.min.js') }}"></script>
    <script src="{{ url('js/jquery.confirm.min.js') }}"></script>
    <script src="{{ url('js/jquery-ui.js') }}"></script>
     <script src="{{ url('js/jquery-ui.min.js') }}"></script>
     

    <script type="text/javascript">
      $('[data-delete]').click(function(e){
        e.preventDefault();
        // If the user confirm the delete
        if (confirm('Voulez vous vraiment effectuer cette suppression ? Les données supprimée le seront de manière irréversible !')) {
            // Get the route URL
            var url = $(this).prop('href');
            // Get the token
            var token = $(this).data('delete');
            // Create a form element
            var $form = $('<form/>', {action: url, method: 'post'});
            // Add the DELETE hidden input method
            var $inputMethod = $('<input/>', {type: 'hidden', name: '_method', value: 'delete'});
            // Add the token hidden input
            var $inputToken = $('<input/>', {type: 'hidden', name: '_token', value: token});
            // Append the inputs to the form, hide the form, append the form to the <body>, SUBMIT !
            $form.append($inputMethod, $inputToken).hide().appendTo('body').submit();
        }
    });

    </script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
 <!--    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script> -->
  </body>
</html>
