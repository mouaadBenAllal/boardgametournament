@extends('layouts.app')
@section('content')
    <section class="contact-2">
        <div class="container">
            <div class="row contact-details">
                <div class="col-sm-8 m-auto text-center">
                    @include('layouts.error')
                    <h2>Rol - Bekijken</h2>
                    <div class="divider"></div>
                    <div class="col-md-12">
                        <input id="name" type="text" class="form-control" name="name" placeholder="Naam" value="{{ $role->name }}" required autofocus readonly>
                    </div>
                    <div class="col-md-12">
                        <select id="authority" class="form-control" name="authority" placeholder="Rol" value="{{ old('authority') }}" required autofocus disabled>
                            <option value="2" <?= $role->authority == 2 ? 'selected' : '' ?>>Normale gebruiker</option>
                            <option value="4"  <?= $role->authority == 4 ? 'selected' : '' ?>>Administrator</option>
                        </select>                    </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection