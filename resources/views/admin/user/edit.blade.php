@extends('layouts.app')

@section('content')
    <br>
    <section class="contact-2">
        <div class="container">
            <div class="col-sm-12 m-auto">
                <h2 class="col-sm-12 m-auto text-center">Gebruiker wijzigen</h2>
                <div class="divider"></div>
                <form class="contact-form mt-4" method="POST" action="{{ route('editUser',  $user->id)}}">
                    {{csrf_field()}}
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="inputVoornaam4">Gebruikersnaam</label>
                            <input type="text" class="form-control" id="inputUsername4" placeholder="Gebruikersnaam"
                                   value="{{$user->username}}" name="username">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputEmail4">Email</label>
                            <input type="text" class="form-control" name="email" id="inputEmail4" placeholder="Email"
                                   value="{{$user->email}}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-2">
                            <label for="inputAddress">Voornaam</label>
                            <input type="text" class="form-control" id="inputVoornaam" name="first_name"
                                   placeholder="Voornaam" value="{{$user->first_name}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputTussenvoegsels">Tussenvoegsels</label>
                            <input type="text" class="form-control" id="inputTussenvoegsels" name="prefix"
                                   value="{{$user->prefix}}"placeholder="Tussenvoegsels">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="inputAchternaam2">Achternaam</label>
                            <input type="text" class="form-control" id="inputAchternaam2" name="last_name"
                                   placeholder="Achternaam" value="{{$user->last_name}}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="inputGeboortedatum">Geboortedatum</label>
                            <input type="date" class="form-control" id="inputDatetime2" name="date_of_birth"
                                   placeholder="Geboortedatum" value="{{$user->date_of_birth}}">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="inputGender">Geslacht</label>
                            <select id="inputGender" class="form-control" name="gender">
                                <option selected>{{$user->gender}}</option>
                                <option>Man</option>
                                <option>Vrouw</option>
                                <option>anders</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputBeschrijving">Beschrijving</label>
                            <textarea class="form-control" id="inputBeschrijving2"
                                      placeholder="Beschrijving" name="description">{{$user->description}}</textarea>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-2">
                            <label for="inputZipcode">Postcode</label>
                            <input type="text" class="form-control" id="inputZipcode" name="zip_code"
                                   placeholder="Postcode" value="{{$user->zip_code}}">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="inputTussenvoegsels">Stad</label>
                            <input type="text" class="form-control" id="inputTussenvoegsels" name="city"
                                   value="{{$user->city}}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="inputCountry2">Land</label>
                            <input type="text" class="form-control" id="inputCountry2" name="country" placeholder="Land"
                                   value="{{$user->country}}">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="inputRol">Rol</label>
                            <select class="form-control" id="inputRol" name="role_id">
                                @foreach($roles as $role)
                                    @if($role->name === $user->role()->get()->first()->name)
                                        <option selected value="{{$user->role()->get()->first()->id}}">
                                            {{$user->role()->get()->first()->name}}
                                        </option>
                                    @else
                                        <option value="{{$role->id}}">
                                            {{$role->name}}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            {{--<input type="text" class="form-control" name="role_id" id="inputRol" placeholder="Rol"--}}
                            {{--value="{{$user->role()->get()->first()->name}}">--}}
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Wijzigen</button>
                </form>
            </div>
        </div>
        @include('layouts.error')
    </section>


@endsection