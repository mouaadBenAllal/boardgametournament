@extends('layouts.app')
@section('content')
    <section class="contact-2">
        <div class="container">
            <div class="row contact-details">
                <div class="col-sm-8 m-auto text-center">
                    @include('layouts.error')
                    <h2>Toernooi - Bekijken</h2>
                    <div class="divider"></div>
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <div class="col-md-12">
                            <input id="name" type="text" class="form-control" name="name" placeholder="Naam (*)" value="{{ old('name', isset($tournament->name) ? $tournament->name : '') }}" required autofocus readonly>
                            @if ($errors->has('name'))
                                <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                        <div class="col-md-12">
                            <textarea id="description" type="text" class="form-control" name="description" placeholder="Beschrijving" required autofocus readonly>{{ old('description', isset($tournament->description) ? $tournament->description : '') }}</textarea>
                            @if ($errors->has('description'))
                                <span class="help-block">
                                <strong>{{ $errors->first('description') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('boardgame') ? ' has-error' : '' }}">
                        <div class="col-md-12">
                            <input id="boardgame" type="text" class="form-control" name="boardgame" placeholder="Bordspel" value="{{ old('boardgame', isset($boardgame->name) ? $boardgame->name : '') }}" required autofocus readonly>
                            @if ($errors->has('boardgame'))
                                <span class="help-block">
                                <strong>{{ $errors->first('boardagame') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('session_size') ? ' has-error' : '' }}">
                        <div class="col-md-12">
                            <input id="session_size" type="number" class="form-control" name="session_size" placeholder="Maximaal aantal spelers" value="{{ old('session_size', isset($tournament->session_size) ? $tournament->session_size : '') }}" required autofocus readonly>
                            @if ($errors->has('session_size'))
                                <span class="help-block">
                                <strong>{{ $errors->first('session_size') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
