@extends('layouts.app')
@section('content')
    <section class="contact-2">
        <div class="container">
            <div class="row contact-details">
                <div class="m-auto">
                    <h2 id="center_h2">Gebruikers</h2>
                    <div class="divider"></div>
                    @extends('layouts.error')
                    @if (count($users) > 0)
                        <table class="table" id="dataTablesTable">
                            <thead>
                            <tr>
                                <th>Naam</th>
                                <th>Email</th>
                                <th>Volledige naam</th>
                                <th>Geboortedatum</th>
                                <th>Rol</th>

                                <th>Acties</th>
                                <th>Ban</th>
                                <th>Verbannen tot</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $key => $user)
                                <tr>
                                    <td>{{$user->username}}</td>
                                    <td>{{$user->email}}</td>
                                    <td>{{$user->first_name}} {{$user->last_name}}</td>
                                    <td>{{$user->date_of_birth}}</td>
                                    <td>{{$user->role()->get()->first()->name}}</td>

                                    <td>
                                        <a href="{{ route('editUser', $user->id) }}"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                    </td>
                                    <td>
                                        @if($user->isBanned())
                                            <a href="{{ route('revokeUser',$user->id) }}"
                                               class="btn btn-warning btn-sm"> Revoke</a>
                                        @else
                                            <a class="btn btn-success ban btn-sm" data-id="{{ $user->id }}"
                                               data-action="{{ URL::route('banUser') }}"> Ban</a>
                                        @endif
                                    </td>

                                    <td>
                                        @if($user->isBanned())
                                            {{ \Carbon\Carbon::parse($user->bans()->first()->expired_at)->format('d/m/Y')}}

                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </section>

@endsection

