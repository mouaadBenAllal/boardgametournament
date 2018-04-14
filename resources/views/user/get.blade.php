@extends('layouts.app')
@section('title', "Profiel $user->username")
@section('content')
<div class="container">
    @include('layouts.error')
    <div class="card mt-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <img src="<?= (empty($user->image) ? 'https://twirpz.files.wordpress.com/2015/06/twitter-avi-gender-balanced-figure.png?w=128' : $user->image)?>" style="height:168px;">
                </div>
                <div class="col-md-8">
                @if(!empty($user->username))
                    <h4>{{$user->username}}</h4>
                @endif
                @if(!empty($user->first_name) && !empty($user->last_name))
                    <h6>{{$user->first_name}} @if(!empty($user->prefix)){{$user->prefix}} @endif {{$user->last_name}}</h6>
                @endif
                @if(!empty($user->city))
                    <h6>{{$user->city }}@if(!empty($user->country)), @endif {{$user->country}}</h6>
                @endif
                @if(!empty($user->gender))
                    <h6>{{$user->gender}}</h6>
                @endif
                @if(!empty($user->date_of_birth))
                    <h6>{{Carbon\Carbon::parse($user->date_of_birth)->format('d/m/Y')}}</h6>
                @endif
                </div>
            </div>
            <p class="mt-3">{{$user->description}}</p>
        </div>
    </div>
        <div class="row mt-3">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Favorieten
                    </div>
                    <ul class="list-group list-group-flush">
                        @foreach ($favorites as $favorite)
                            <li class="list-group-item">{{$favorite->boardgame()->get()->first()->name}}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Ranking
                    </div>
                    <ul class="list-group list-group-flush">
                        @foreach ($ranking as $row)
                            <li class="list-group-item">{{$row['boardgame_name']}} - {{$row['win']}}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Achievements
                    </div>
                    <ul class="list-group list-group-flush">
                        @foreach ($achievementList as $achievement)
                            <li class="list-group-item">{{$achievement}}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
</div>
@endsection
