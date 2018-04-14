@extends('layouts.app')
@section('title', 'Profiel update')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
@section('content')
  <div class="container mt-3">
     <form action="{{ route('user/edit', $user->id) }}" method="post" enctype="multipart/form-data">
        <div class="card">
           {{csrf_field()}}
           <div class="card-body">
              <div class="input-group">
                 <input type="text" name="first_name" id="name" class="form-control" value="{{$user->first_name}}" placeholder="Naam">
                 <input type="text" name="prefix" id="name" class="form-control" style="margin-left:5px" value="{{$user->prefix}}" placeholder="Tussenvoegsel">
                 <input type="text" name="last_name" id="name" class="form-control" style="margin-left:5px" value="{{$user->last_name}}" placeholder="Achternaam">
              </div>
              <div class="mt-3">
                  <p>Huidige profielfoto:</p>
                  <img src="{{$user->image}}" class="img" width="120px">
              </div>
              <div class="form-group mt-3">
                 <label class="custom-file">
                 <input type="file" id="file" name="image" accept="image/*" class="custom-file-input form-control-file">
                 <span class="custom-file-control"></span>
                 </label>
              </div>
              <div class="form-check">
                 <label class="form-check-label">
                 <input class="form-check-input" type="radio" name="gender" id="exampleRadios1" value="Man" <?= ($user->gender == 'Man'?'checked' :'' )?> >
                 Man
                 </label>
                 <label class="form-check-label">
                 <input class="form-check-input" type="radio" name="gender" id="exampleRadios1" value="Vrouw" <?= ($user->gender == 'Vrouw'?'checked' :'' )?> >
                 Vrouw
                 </label>
                 <label class="form-check-label">
                 <input class="form-check-input" type="radio" name="gender" id="exampleRadios1" value="Anders" <?= ($user->gender == 'Anders'?'checked' :'' )?> >
                 Anders
                 </label>
                 <label class="form-check-label">
                 <input class="form-check-input" type="radio" name="gender" id="exampleRadios1" value="" <?= ($user->gender == ''?'checked' :'' )?> >
                 Ik zeg het liever niet
                 </label>
              </div>
              <div class="form-group">
                 <input type="date" name="birthday" id="datepicker" value="{{$user->date_of_birth}}" class="form-control" placeholder="Geboortedatum">
              </div>
              <div class="form-group">
                 <textarea name="description" class="form-control" placeholder="Biografie">{{$user->description}}</textarea>
              </div>
              <div class="form-group">
                 <input type="text" class="form-control" name="city" id="name" value="{{$user->city}}" placeholder="Stad">
              </div>
              <div class="form-group">
                  <select name="country" class="form-control">
                      <option disabled selected>-- Selecteer een land --</option>
                    @foreach ($countries as $country)
                        <option value="{{$country}}"<?= ($user->country == $country ? 'selected' : '')?>>{{$country}}</option>
                    @endforeach
                  </select>
              </div>
              <input type="submit" value="Opslaan" class="btn btn-primary">
           </div>
     </form>
     </div>
  </div>
@endsection
@section('script')
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
@endsection
