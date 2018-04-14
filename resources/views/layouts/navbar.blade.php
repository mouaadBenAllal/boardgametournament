@inject('notifications', 'App\Components\NotificationCollection')

<section class="cover-5 text-center" style="min-height: 0px;">
    <nav class="navbar navbar-expand-lg navbar-light navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="/">{{ config('app.name', 'Boardgame Tournament') }}</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse pull-xs-right justify-content-end" id="navbarSupportedContent">
                <ul class="navbar-nav mt-2 mt-md-0">
                    <li class="nav-item active">
                        <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('boardgame')}}">Bordspellen</a>
                    </li>
                    @if(!\App\Handlers\UserHandler::isLoggedIn())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('tournament') }}">Toernooien</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Toernooien
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                @if(count(\Illuminate\Support\Facades\Auth::user()->boardgames > 0))
                                    <a class="dropdown-item" href="{{ route('ownTournaments') }}">Eigen toernooien</a>
                                @endif
                                <a class="dropdown-item" href="{{ route('tournament') }}">Overzicht</a>
                                <a class="dropdown-item" href="{{route('/tournament/create')}}">Aanmaken</a>
                            </div>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" href="/forum">Forum</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/#contact">Contact</a>
                    </li>
                    <li class="nav-item nav-link">|</li>
                    @guest
                        <li class="nav-item"><a href="{{ route('login') }}" class="nav-link">Inloggen</a></li>
                    @endguest
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ Auth::user()->username }}
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item" href="/user/get/{{Auth::User()->username}}">Profiel</a>
                                        <a class="dropdown-item" href="{{ route("notifications") }}">Notificaties</a>
                                        <a class="dropdown-item" href="{{route('user/edit')}}">Instellingen</a>
                                @if (\App\Handlers\UserHandler::isAdmin())
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('dashboard') }}">Admin-paneel</a>
                                @endif
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Uitloggen
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </a>
                            </div>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
</section>
