<nav class="navbar navbar-expand-md navbar-dark bg-danger shadow-sm">
    <!--<div class="container">-->
        <a class="navbar-brand" href="{{ url('/') }}">
            <b>{{ config('app.name', 'SEO Soporte') }}</b>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                @if (!Auth::guest() && !empty($menu))
                    @foreach($menu as $carpeta => $content )
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown1" href="#" class="nav-link dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ $carpeta }} <span class="caret"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-left" aria-labelledby="navbarDropdown1">
                                @foreach($content as $value)
                                    {{--@if(isset($value["carpetaPadre"]))
                                        <li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" href="#">{{$value["carpetaPadre"]}}</a>
                                            <ul class="dropdown-menu">
                                                <a class="dropdown-item" href="/{{$value['path']}}">{{ $value["formulario"]}}</a>
                                            </ul>
                                        </li>
                                    @else --}}
                                        <a class="dropdown-item" href="/{{$value['path']}}">{{ $value["formulario"]}}</a>
                                   {{-- @endif--}}
                                @endforeach  
                            </div>
                        </li>                              

                    @endforeach
                @endif
            </ul>
            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Iniciar Sesión') }}</a>
                    </li>
                @else
                    <li class="nav-item avatar dropdown">
                        <a class="nav-link dropdown-toggle waves-effect waves-light" id="navbarDropdownMenuLink-5" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <span class="badge badge-danger ml-2" id="cantidadNotif">0</span>
                            <i class="fas fa-bell"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg-right dropdown-secondary" id="notif" aria-labelledby="navbarDropdownMenuLink-5">
                            <!--<a class="dropdown-item waves-effect waves-light" href="#">Action <span class="badge badge-danger ml-2">4</span></a>
                            <a class="dropdown-item waves-effect waves-light" href="#">Another action <span class="badge badge-danger ml-2">1</span></a>
                            <a class="dropdown-item waves-effect waves-light" href="#">Something else here <span class="badge badge-danger ml-2">4</span></a>-->
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                {{ __('Cerrrar Sesión') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    <!--</div>-->
</nav>