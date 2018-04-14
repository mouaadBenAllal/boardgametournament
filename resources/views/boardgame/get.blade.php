@extends('layouts.app')
@section('title')
{{$boardgame->name}}
@endsection
@section('content')
    <section class="contact-2">
        <div class="container">
            <div class="row contact-details">
                @include('layouts.error')
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <img src="<?= (empty($boardgame->image) ? 'http://via.placeholder.com/200x200' : $boardgame->image)?> " class="img-fluid rounded" alt="Image {{ $boardgame->name }}">
                        </div>
                        <div class="col-md-2">
                        @auth
                            <form method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="like" value="1"/>
                            <button class="fa fa-thumbs-up"></button><span> ({{ $likes }}) {{str_plural('Like', $likes)}}</span>
                            </form>

                        <br>
                            <form method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="like" value="0"/>
                                <button class="fa fa-thumbs-down"></button><span> ({{ $dislikes }}) {{str_plural('Dislike', $dislikes)}}</span>
                            </form>
                        @endauth
                        </div>
                        <div class="col-md-8 col-sm-12">
                            <h3>{{ $boardgame->name }}</h3>
                            <div class="mt-3"></div>
                            <p>{{ $boardgame->description }}</p>
                        </div>
                        <div class="card col-md-12 mt-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <h6><b>Spelers:</b> {{ $boardgame->min_players }} - {{ $boardgame->max_players }}</h6>
                                    </div>
                                    <div class="col-md-4">
                                        <h6><b>Geschatte speeltijd:</b> {{ $boardgame->avg_time }} minuten</h6>
                                    </div>
                                    <div class="col-md-4">
                                        @foreach($categories as $category)
                                            @if($category->id == $boardgame->category_id)
                                                <h6><b>Categorie:</b> {{ $category->name }}</h6>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                        <?php
                            // Define the facade for the ranking:
                            $rankingFacade = new \App\Facades\RankingFacade();
                            // Define the ranking for the boardgame:
                            $ranking = $rankingFacade->boardgame($boardgame->id);
                            ?>
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        Ranking (1 - 10)
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        @for($i = 0; $i < count($ranking) && $i < 10; $i++)
                                            <li class="list-group-item"><?= ($i + 1) ?>. <a href="/user/get/<?= $ranking[$i]['username'] ?>">{{ $ranking[$i]['username'] }}</a>  - {{  $ranking[$i]['win'] }}</li>
                                        @endfor
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        Ranking (10 - 20)
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        @for($i = 10; $i < count($ranking) && $i < 20; $i++)
                                            <li class="list-group-item"><?= ($i + 1) ?>. <a href="/user/get/<?= $ranking[$i]['username'] ?>">{{ $ranking[$i]['username'] }}</a>   - {{  $ranking[$i]['win'] }}</li>
                                        @endfor
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        Ranking (20 - 30)
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        @for($i = 20; $i < count($ranking) && $i < 30; $i++)
                                            <li class="list-group-item"><?= ($i + 1) ?>. <a href="/user/get/<?= $ranking[$i]['username'] ?>">{{ $ranking[$i]['username'] }}</a>   - {{  $ranking[$i]['win'] }}</li>
                                        @endfor
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
@endsection
