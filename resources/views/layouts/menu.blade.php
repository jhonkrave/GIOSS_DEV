<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Scripts -->
        <!-- <script src="{{ asset('js/app.js') }}"></script> -->
        <!-- <script src="{{ asset('js/menu.js') }}"></script> -->
        
        {{ Html::script(asset("js/jquery-3.1.0.min.js")) }}
        {{ Html::script(asset("css/bootstrap-3.3.7-dist/js/bootstrap.min.js")) }}
        <!-- {{ Html::script(asset("js/menu.js")) }} -->
        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Styles -->
        <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet">  -->
        {{ Html::style(asset("css/bootstrap-3.3.7-dist/css/bootstrap.min.css")) }}
        {{ Html::style(asset("css/font-awesome.css")) }}
        {{ Html::style(asset("css/menu.css")) }}
        <!-- Scripts -->
            
        <script>
            window.Laravel = {!! json_encode([
                'csrfToken' => csrf_token(),
            ]) !!};
        </script>
    </head>
    <body>

    


        <nav class="main-menu">


   
<!-- <div class="settings"></div> -->
<div class="scrollbar" id="style-1">
      
<ul>
  
    <li>                                   
    <a href="{{ url('/home') }}">
    <i class="fa fa-home fa-lg"></i>
    <span class="nav-text">Home</span>
    </a>
    </li>
    @if (Auth::user()->roleid == 1)
       <li class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-fw fa-plus"></i> Administracion de Usuarios <span class="caret"></span></a>
      <ul class="dropdown-menu" role="menu">
        <li><a href="{{ url('/registro') }}">Adicionar Usuario</a></li>
      </ul>
    </li> 
    @endif
    <li class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-fw fa-plus"></i> Administracion de Archivos <span class="caret"></span></a>
      <ul class="dropdown-menu" role="menu">
        <li><a href="{{ url('/upload_files') }}">Carcar archivos</a></li>
      </ul>
    </li>

       
 
 
  
</ul>

  
    <li>
                                       
    <a href="#">
    <i class="fa fa-question-circle fa-lg"></i>
    <span class="nav-text">Help</span>
    </a>
    </li>  


    
  
<ul class="logout">
    <li>
                       <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                             <i class="fa fa-sign-out"></i>
                            <span class="nav-text">
                                salir 
                            </span>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                            
                        </a>
    </li>  
</ul>
        </nav>
@yield('content')
    </body>
</html>
    <!-- /#wrapper -->