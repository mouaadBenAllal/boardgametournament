@extends('layouts.app')
@section('title', 'Toernooi aanpassen')
@section('content')
<div class="container mt-3">
    @include('layouts.error')
  <div class="card">
    <form action="/tournament/edit/{{$tournament->token}}" method="post">
          {{csrf_field()}}
          <div class="card-body">
          <input type="hidden" name="creator" value="{{Auth::id()}}">
             <div class="form-group">
               <label for="name">Naam van toernooi: </label>
                <input type="text" name="name" value="{{ old('name') ? old('name') : $tournament->name}}" id="name" class="form-control" placeholder="Naam">
             </div>
             <div class="form-group">
               <label for="name">Beschrijving: </label>
               <textarea name="description" class="form-control" placeholder="Beschrijving">{{ old('description') ? old('description') : $tournament->description}}</textarea>
             </div>
             <div class="form-group">
               <label for="name">Bordspel: </label>
                <select title="boardgame" name="boardgame" class="form-control">
                    @foreach ($boardgames as $boardgame)
                        <option value="{{$boardgame->id}}" {{ old('boardgame') == $boardgame->id ? 'selected' : ($boardgame->id == $tournament->boardgame_id && old('boardgame') == null ? 'selected' : '' ) }}>{{$boardgame->name}}</option>
                    @endforeach
                </select>
             </div>
             <div class="form-group">
                <label for="name">Ronde grootte: </label>
                 <input type="number" class="form-control" name="session_max" value="{{ old('session_max') ? old('session_max') : $tournament->session_size }}" id="name" min="1" placeholder="">
             </div>
             <div class="form-check">
                <label for="name">Prive: </label>
                <input type="hidden" name="private" value="0">
                 <input type="checkbox" name="private" value="1" <?= old('private') == 1 ? 'checked' : ($tournament->private == 1 ? 'checked' : '')?>>
             </div>
             <input type="submit" value="Opslaan" class="btn btn-primary">
         </div>
          </form>
        </div>
      </div>
@endsection
