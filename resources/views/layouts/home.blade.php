@include('layouts.app')
@section('title')
    Boardgame Tournament
@endsection
@section('content')
    <section class="cover-5 text-center">
        <div class="container">
            @include('layouts.error')
        </div>
        <div class="cover-container pb-5">
            <div class="cover-inner container">
                <p class="lead "></p>
                <h1 class="jumbotron-heading"><em>Welkom</em> bij <br><strong> {{ config('app.name', 'Boardgame Tournament') }}</strong></h1>
                <div class="divider"></div>
                <h6>Boardgame Tournament is een platform voor bordspelspelers om belangrijke en relevante informatie te kunnen
                    zoeken over diverse bordspellen. Na het registeren kun je een eigen toernooi met een bordspel starten,
                    waarin de toernooi gegevens worden opgeslagen om bij te houden en op een later de uitslag bekeken kan worden.
                </h6>
                <br>
                <div class="row justify-content-around">
                    @foreach($boardgames as $boardgame)
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <a href="{{ route('boardgame/get', $boardgame->id) }}">
                                <img src="<?= (empty($boardgame->image) ? 'http://via.placeholder.com/200x200' : $boardgame->image)?>" class="img-fluid rounded" style="width: 200px; height: 200px;" alt="Image {{ $boardgame->name }}">
                            </a>
                            <h4 class="mt-4">{{ $boardgame->name }}</h4>
                            @foreach(\App\Models\Category::all() as $category)
                                @if ($boardgame->category_id == $category->id)
                                    <p>{{ $category->name }}</p>
                                @endif
                            @endforeach()
                        </div>
                    @endforeach
                    <a class="col-md-12" href="{{ route('boardgame') }}">Meer <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
                </div>

                {{--If user is signed in and has liked at least 1 game, show suggestions content--}}
                @if(Auth::check() && count($suggestions) > 0)
                <br>
                <br>
                <h4>Misschien vind je deze spellen ook leuk</h4>
                <div class="divider"></div>
                <div class="row justify-content-around">
                    @foreach($suggestions as $suggestion)
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <a href="{{ route('boardgame/get', $suggestion->id) }}">
                                <img src="<?= (empty($suggestion->image) ? 'http://via.placeholder.com/200x200' : $suggestion->image)?>" class="img-fluid rounded" style="width: 200px; height: 200px;" alt="Image {{ $boardgame->name }}">
                            </a>
                            <h4 class="mt-4">{{ $suggestion->name }}</h4>
                            @foreach(\App\Models\Category::all() as $category)
                                @if ($suggestion->category_id == $category->id)
                                    <p>{{ $category->name }}</p>
                                @endif
                            @endforeach()
                        </div>
                    @endforeach
                </div>
                <div class="divider"></div>
                @endif
                <br>
            </div>
        </div>
    </section>
    <section class="features-4 text-center" id="mogelijkheden">
        <div class="container">
            <div class="row justify-center">
                <div class="col-md-8 text-center">
                    <h2>Mogelijkheden</h2>
                    <p class="lead mt-3">Bekijk gauw de bordspellen, toernooien en de jouw ranking binnen de toernooien</p>
                    <div class="divider"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-feature">
                    <div class="rounded-circle justify-center">
                        <em class="fa fa-2x fa-gamepad"></em>
                    </div>
                    <h4>Bordspellen</h4>
                    <p>Bekijk alle beschikbare bordspellen die wij u aan te bieden hebben.</p>
                </div>
                <div class="col-md-4 col-feature">
                    <div class="rounded-circle justify-center">
                        <em class="fa fa-2x fa-users"></em>
                    </div>
                    <h4>Toernooien</h4>
                    <p>Speel tegen vrienden en/of familie of bij een vereneging.
                        Schijf u snel in op uw favoriet en stijd tegen anderen.</p>
                </div>
                <div class="col-md-4 col-feature">
                    <div class="rounded-circle justify-center">
                        <em class="fa fa-2x fa-bars"></em>
                    </div>
                    <h4>Ranking</h4>
                    <p>Heeft u een toernooi gespeeld en/of wilt u een bekende verslaan. Bekijk de uitslagen
                    van gespeeld toernooien en wordt beter.</p>
                </div>
            </div>
        </div>
    </section>
    @include('layouts.contact')