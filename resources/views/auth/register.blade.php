@extends('layouts.app')
@section('title', 'Registreren')
@section('content')
<section class="contact-2">
    <div class="container">
        <div class="row contact-details">
            <div class="col-sm-8 m-auto text-center">
                <h2>Registreren</h2>
                <div class="divider"></div>
                <form class="contact-form mt-4" method="POST" action="{{ route('register') }}">
                    {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">

                        <div class="col-md-12">
                            <input id="username" type="text" class="form-control" name="username" placeholder="Gebruikersnaam" value="{{ old('username') }}" required autofocus>

                            @if ($errors->has('username'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('username') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">

                        <div class="col-md-12">
                            <input id="email" type="email" class="form-control" name="email" placeholder="E-mail" value="{{ old('email') }}" required>

                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">

                        <div class="col-md-12">
                            <input id="password" type="password" class="form-control" placeholder="Wachtwoord" name="password" required>

                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">

                        <div class="col-md-12">
                            <input id="password-confirm" type="password" class="form-control" placeholder="Herhaal wachtwoord" name="password_confirmation" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">
                                Registreer
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</section>
@endsection
