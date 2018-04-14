@extends('layouts.app')
@section('content')
    <section class="contact-2">
        <div class="container">
            <div class="row contact-details">
                <div class="col-sm-8 m-auto text-center">
                    @include('layouts.error')
                    <h2>Bordspel - Aanmaken</h2>
                    <div class="divider"></div>
                    <form class="contact-form mt-4" method="POST" action="{{ route('admin/boardgame/create') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <div class="col-md-12">
                                <input id="name" type="text" class="form-control" name="name" placeholder="Naam (*)" value="{{ old('name') }}" required autofocus>
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            <div class="col-md-12">
                                <textarea id="description" type="text" class="form-control" name="description" placeholder="Beschrijving" value="{{ old('description') }}" autofocus></textarea>
                                @if ($errors->has('description'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('description') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                            <div class="col-md-12">
                                <input id="i" type="file" accept="*/image" class="form-control-file" name="image" value="{{ old('image') }}" autofocus></input>
                                @if ($errors->has('image'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('image') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('min_players') ? ' has-error' : '' }}">
                            <div class="col-md-12">
                                <input id="min_players" type="number" class="form-control" name="min_players" placeholder="Minimaal aantal spelers" value="{{ old('min_players') }}" required autofocus>
                                @if ($errors->has('min_players'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('min_players') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('max_players') ? ' has-error' : '' }}">
                            <div class="col-md-12">
                                <input id="max_players" type="number" class="form-control" name="max_players" placeholder="Maximaal aantal spelers" value="{{ old('max_players') }}" required autofocus>
                                @if ($errors->has('max_players'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('max_players') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('avg_time') ? ' has-error' : '' }}">
                            <div class="col-md-12">
                                <input id="avg_time" type="number" class="form-control" name="avg_time" placeholder="Geschatte tijdsduur spel in minuten" value="{{ old('avg_time') }}" required autofocus>
                                @if ($errors->has('avg_time'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('avg_time') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('category_id') ? ' has-error' : '' }}">
                            <div class="col-md-12">
                                <select id="category_id" name="category_id" class="form-control" required autofocus>
                                    <option disabled selected value> -- Selecteer een categorie -- </option>
                                    @foreach (\App\Models\Category::all() as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('category_id'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('category_id') }}</strong>
                                </span>
                                @endif
                            </div>
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
