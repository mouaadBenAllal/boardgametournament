@extends('layouts.app')
@section('title', 'Create Tournament')
@section('content')
<div class="container mt-3">
    @include('layouts.error')
    <div class="card">
    <form action="/tournament/create" method="post">
          {{csrf_field()}}
          <div class="card-body">
          <input type="hidden" name="creator" value="{{Auth::id()}}">
             <div class="form-group">
               <label for="name">Naam van toernooi: </label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name')}}" placeholder="Naam" autofocus required>
             </div>
             <div class="form-group">
               <label for="name">Beschrijving: </label>
               <textarea name="description" rows="3" class="form-control" placeholder="Beschrijving">{{ old('description')}}</textarea>
             </div>
             <div class="form-group">
               <label for="name">Bordspel: </label>
                <select name="boardgame" class="form-control" required>
                  <option value="" disabled {{ old('boardgame') ? '' :'selected' }}>-- Selecteer bordspel --</option>
                  @foreach ($boardgames as $boardgame)
                    <option value="{{$boardgame->id}}" {{ old('boardgame') == $boardgame->id ? 'selected' :'' }}>{{$boardgame->name}}</option>
                  @endforeach
                </select>
             </div>
             <div class="form-group">
                <label for="name">Sessie grootte: </label>
                 <input type="number" class="form-control" name="session_max" value="{{ old('session_max') ? old('session_max') :'1' }}" id="name" min="1" placeholder="" required>
             </div>
             <div class="form-check">
                <label for="name">Prive: </label>
                <input type="hidden" name="private" value="0">
                 <input type="checkbox" name="private" value="1" <?= old('private') == 1 ? 'checked' : ''?>>
             </div>
             <input type="submit" value="Opslaan" class="btn btn-primary">
         </div>
          </form>
        </div>
      </div>
@endsection
