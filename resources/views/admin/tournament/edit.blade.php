@extends('layouts.app')
@section('content')
    <section class="contact-2">
        <div class="container">
            <div class="row contact-details">
                <div class="col-sm-8 m-auto text-center">
                    @include('layouts.error')
                    <h2>Toernooi - Wijzigen</h2>
                    <div class="divider"></div>
                    <form class="contact-form mt-4" method="POST" action="{{ route('admin/tournament/edit', $tournament->token) }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="creator" value="{{Auth::id()}}">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <div class="col-md-12">
                                <input id="name" type="text" class="form-control" name="name" placeholder="Naam (*)" value="{{ old('name', isset($tournament->name) ? $tournament->name : '') }}" required autofocus>
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            <div class="col-md-12">
                                <textarea id="description" type="text" class="form-control" name="description" placeholder="Beschrijving" autofocus>{{ old('description', isset($tournament->description) ? $tournament->description : '') }}</textarea>
                                @if ($errors->has('description'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('description') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('boardgame_id') ? ' has-error' : '' }}">
                            <div class="col-md-12">
                                <select id="boardgame_id" name="boardgame_id" class="form-control" required autofocus>
                                    <option disabled selected value> -- Selecteer een bordspel -- </option>
                                    @foreach ($boardgames as $boardgame)
                                        <option value="{{ $boardgame->id }}" @php echo ($boardgame->id == $tournament->boardgame_id) ? 'selected' : '' @endphp>{{ $boardgame->name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('category_id'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('category_id') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('session_size') ? ' has-error' : '' }}">
                            <div class="col-md-12">
                                <input id="session_size" type="number" class="form-control" name="session_size" placeholder="Maximaal aantal spelers" value="{{ old('session_size', isset($tournament->session_size) ? $tournament->session_size : '') }}" required autofocus>
                                @if ($errors->has('session_size'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('session_size') }}</strong>
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
                                    Aanpassen
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
