@extends('layouts.app')
@section('content')
    <section class="contact-2">
        <div class="container">
            @include('layouts.error')
            <div class="row contact-details">
                <div class="col-sm-8 m-auto text-center">
                    <h2>Toernooi - Aanmaken</h2>
                    <div class="divider"></div>
                    <form class="contact-form mt-4" method="POST" action="{{ route('admin/tournament/create') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="creator" value="{{Auth::id()}}">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <div class="col-md-12">
                                <input type="text" name="name" id="name" class="form-control" placeholder="Naam (*)"  value="{{ old('name') }}" required autofocus>
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            <div class="col-md-12">
                                <textarea type="text" id="description"  class="form-control" name="description" placeholder="Beschrijving" value="{{ old('description') }}" autofocus></textarea>
                                @if ($errors->has('description'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('description') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('boardgame_id') ? ' has-error' : '' }}">
                            <div class="col-md-12">
                                <select id="boardgame_id" name="boardgame" class="form-control" required autofocus>
                                    <option disabled selected value> -- Selecteer een bordspel -- </option>
                                    @foreach ($boardgames as $boardgame)
                                        <option value="{{ $boardgame->id }}" {{ (old('boardgame_id') == $boardgame->id ? 'selected' : '')  }}>{{ $boardgame->name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('boardgame_id'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('boardgame_id') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('session_max') ? ' has-error' : '' }}">
                            <div class="col-md-12">
                                <input id="session_max" type="number" class="form-control" name="session_max" placeholder="Maximaal aantal spelers" value="{{ old('session_max') }}" required autofocus>
                                @if ($errors->has('session_max'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('session_max') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-check">
                            <label for="name">Prive: </label>
                            <input type="hidden" name="private" value="0">
                            <input type="checkbox" name="private" value="1">
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    Aanmaken
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
