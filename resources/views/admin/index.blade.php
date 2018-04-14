@extends('layouts.app')
@section('content')
    <section class="contact-2">
        <div class="container">
            <div class="row contact-details">
                <div class="col-12">
                    @include('layouts.error')
                    <h2 class="jumbotron-heading text-center"><strong>Admin-paneel</strong></h2>
                    <div class="divider"></div>
                    <div class="mt-4">
                        <div class="row justify-content-center">
                            @foreach($panel as $page)
                            <div class="col-md-4 col-sm-6 col-xs-12 text-center">
                                <a href="{{ route($page['route']) }}">
                                    <img src="http://via.placeholder.com/200x200" class="img-fluid rounded" style="width: 200px; height: 200px;" alt="Image">
                                </a>
                                <h4 class="mt-4">{{ $page['name'] }}</h4>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
