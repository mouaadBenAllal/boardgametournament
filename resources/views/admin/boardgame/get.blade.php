@extends('layouts.app')
@section('content')
    <section class="contact-2">
        <div class="container">
            <div class="row contact-details">
                <div class="col-sm-8 m-auto text-center">
                    @include('layouts.error')
                    <h2>Bordspel - Bekijken</h2>
                    <div class="divider"></div>
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <div class="col-md-12">
                            <input id="name" type="text" class="form-control" name="name" placeholder="Naam (*)" value="{{ old('name', isset($boardgame->name) ? $boardgame->name : '') }}" required autofocus readonly>
                            @if ($errors->has('name'))
                                <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                        <div class="col-md-12">
                            <textarea id="description" type="text" class="form-control" name="description" placeholder="Beschrijving" required autofocus readonly>{{ old('description', isset($boardgame->description) ? $boardgame->description : '') }}</textarea>
                            @if ($errors->has('description'))
                                <span class="help-block">
                                <strong>{{ $errors->first('description') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('min_players') ? ' has-error' : '' }}">
                        <div class="col-md-12">
                            <input id="min_players" type="number" class="form-control" name="min_players" placeholder="Minimaal aantal spelers" value="{{ old('min_players', isset($boardgame->min_players) ? $boardgame->min_players : '') }}" required autofocus readonly>
                            @if ($errors->has('min_players'))
                                <span class="help-block">
                                <strong>{{ $errors->first('min_players') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('max_players') ? ' has-error' : '' }}">
                        <div class="col-md-12">
                            <input id="max_players" type="number" class="form-control" name="max_players" placeholder="Maximaal aantal spelers" value="{{ old('max_players', isset($boardgame->max_players) ? $boardgame->max_players : '') }}" required autofocus readonly>
                            @if ($errors->has('max_players'))
                                <span class="help-block">
                                <strong>{{ $errors->first('max_players') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('avg_time') ? ' has-error' : '' }}">
                        <div class="col-md-12">
                            <input id="avg_time" type="number" class="form-control" name="avg_time" placeholder="Geschatte tijdsduur spel in minuten" value="{{ old('avg_time', isset($boardgame->avg_time) ? $boardgame->avg_time : '') }}" required autofocus readonly>
                            @if ($errors->has('avg_time'))
                                <span class="help-block">
                                <strong>{{ $errors->first('avg_time') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('category_id') ? ' has-error' : '' }}">
                        <div class="col-md-12">
                            <select id="category_id" name="category_id" class="form-control" required autofocus disabled>
                                <option disabled selected value> -- Selecteer een categorie -- </option>
                                @foreach (\App\Models\Category::all() as $category)
                                    <option value="{{ $category->id }}" @php echo ($category->id == $boardgame->category_id) ? 'selected' : '' @endphp>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('category_id'))
                                <span class="help-block">
                                <strong>{{ $errors->first('category_id') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
