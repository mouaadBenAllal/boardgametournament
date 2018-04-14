@extends('layouts.app')
@section('title', 'Bordspellen')
@section('content')
    <section class="contact-2">
        <div class="container">
            <div class="row contact-details">
                <div class="col-12">
                    @include('layouts.error')
                    <h2 class="jumbotron-heading text-center"><strong>Overzicht bordspellen</strong></h2>
                    <div class="divider"></div>
                    <div class="row">
                        <div class="col-sm-12">
                            <form method="GET" action="{{ route('boardgame') }}">
                                <div class="input-group stylish-input-group">
                                    <input type="text" class="form-control"  placeholder="Bordspel zoeken" name="search">
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
                            @forelse($boardgames as $boardgame)
                                <div class="col-md-4 col-sm-6 col-xs-12 text-center">
                                    <a href="{{ route('boardgame/get', $boardgame->id) }}">
                                        <img src="<?= (empty($boardgame->image) ? 'http://via.placeholder.com/200x200' : $boardgame->image)?>" class="img-fluid rounded" style="width: 200px; height: 200px;" alt="Image {{ $boardgame->name }}">
                                    </a>
                                    <h4 class="mt-4">{{ $boardgame->name }}</h4>
                                    @foreach($categories as $category)
                                        @if ($boardgame->category_id == $category->id)
                                            <p>{{ $category->name }}</p>
                                        @endif
                                    @endforeach()
                                </div>
                                @empty
                                <div class="col-md-12 col-sm-6 text-center">
                                    <h4>Er zijn momenteel geen bordspellen.</h4>
                                </div>
                            @endforelse()
                        </div>
                    </div>
                    @if($boardgames instanceof \Illuminate\Pagination\LengthAwarePaginator )
                        <nav>
                        {{ $boardgames->links() }}
                        </nav>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
