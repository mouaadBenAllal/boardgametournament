@extends('layouts.app')
@section('content')
    <section class="contact-2">
        <div class="container">
            <div class="row contact-details">
                <div class="col-sm-8 m-auto text-center">
                    @include('layouts.error')
                    <h2>Categorie - Bekijken</h2>
                    <div class="divider"></div>
                    <div class="col-md-12">
                        <input id="name" type="text" class="form-control" name="name" placeholder="Naam" value="{{ $category->name }}" required autofocus readonly>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection