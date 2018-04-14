@extends('layouts.app')

@section('content')
    <section class="contact-2">
        <div class="container">
            <div class="row contact-details">
                @include('layouts.error')
                <div class="col-sm-8 m-auto text-center">
                    <h2>Ranking</h2>
                    <h2 style="font-weight: bold;">{{ $boardgame->name }}</h2>
                    <div class="divider"></div>
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <table class="table ranking">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Gebruikersnaam</th>
                            <th>Win</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($ranking as $key => $row)
                            <tr>
                                <td>{{ $key }}</td>
                                <td>{{ $row['username'] }}</td>
                                <td>{{ $row['win'] }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>
@endsection
