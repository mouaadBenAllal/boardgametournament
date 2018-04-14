@extends('layouts.app')
@section('content')
    <section class="contact-2">
        <div class="container">
            <div class="row contact-details">
                <div class="col-sm-8 m-auto text-center">
                    @include('layouts.error')
                    <h2>Rol - Aanmaken</h2>
                    <div class="divider"></div>
                    <form class="contact-form mt-4" method="POST" action="{{ route('admin/role/create') }}">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <div class="col-md-12">
                                <input id="name" type="text" class="form-control" name="name" placeholder="Naam" value="{{ old('name') }}" required autofocus>
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('authority') ? ' has-error' : '' }}">
                            <div class="col-md-12">
                                <select id="authority" class="form-control" name="authority" placeholder="Rol" value="{{ old('authority') }}" required autofocus>
                                    <option value="2">Normale gebruiker</option>
                                    <option value="4">Administrator</option>
                                </select>
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