@extends('layouts.app')
@section('title', 'Toernooien overzicht')
@section('content')
    <section class="contact-2">
        <div class="container">
            <div class="row contact-details">
                <div class="col-12">
                    @include('layouts.error')
                    <h2 class="jumbotron-heading text-center"><strong>Overzicht toernooien</strong></h2>
                    <div class="divider"></div>
                    <div class="row">
                        <div class="col-sm-12">
                            <form method="GET" action="{{ route('tournament') }}">
                                <div class="input-group stylish-input-group">
                                    <input type="text" class="form-control" placeholder="Toernooi zoeken"
                                           name="search">
                                    <span class="input-group-addon">
                                <button class="btn btn-borderless bg-transparent" type="submit">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </button>
                            </span>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="row justify-content-center">
                            @forelse($tournaments as $tournament)
                                <div class="col-md-4 col-sm-6 col-xs-12 text-center">
                                    <a href="{{ route('/tournament/get', $tournament->token) }}">
                                        <img src="<?= $tournament->boardgame()->first()->image ? $tournament->boardgame()->first()->image : "http://via.placeholder.com/200x200"?>" style="width: 200px; height: 200px;" class="img-fluid rounded" alt="Image {{ $tournament->name }}">
                                    </a>
                                    <h4 class="mt-4">{{ $tournament->name }}</h4>
                                    <p>{{ $tournament->boardgame()->first()->name }}</p>
                                </div>
                            @empty
                                <div class="col-md-12 col-sm-6 text-center">
                                    <h4>Er zijn momenteel geen toernooien.</h4>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    @if($tournaments instanceof \Illuminate\Pagination\LengthAwarePaginator )
                        <nav>
                            {{ $tournaments->links() }}
                        </nav>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
