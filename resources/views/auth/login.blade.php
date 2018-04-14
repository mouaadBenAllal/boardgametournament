@extends('layouts.app')
@section('title', 'login')
@section('content')
    <section class="contact-2">
        <div class="container">
            <div class="row contact-details">
                <div class="col-sm-8 m-auto text-center">
                    @include('layouts.error')
                    <h2>Inloggen</h2>
                    <div class="divider"></div>
                        <form class="contact-form mt-4" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">

                            <div class="col-md-12">
                                <input id="email" type="email" class="form-control" name="email" placeholder="E-mail" value="{{ old('email') }}" required autofocus>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">

                            <div class="col-md-12">
                                <input id="password" type="password" class="form-control" placeholder="Wachtwoord" name="password" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Onthoud mijn gegevens
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    Inloggen
                                </button>

                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    Wachtwoord vergeten?
                                </a>
                            </div>
                        </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    Heeft u nog geen account?<a class="btn btn-link" href="{{ route('register') }}">Registreer hier</a>
                                </div>
                            </div>
                    </form>
                </div>

            </div>
        </div>
    </section>
@endsection