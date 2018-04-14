@extends('layouts.app')

@section('content')
    <section class="contact-2">
        <div class="container">
            <div class="row contact-details">
                @include('layouts.error')
                <div class="col-sm-8 m-auto text-center">
                    <h2>Ranking</h2>
                    <h2 style="font-weight: bold;">{{ $user->username }}</h2>
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
                            <th>Boardgame</th>
                            <th>Win</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($ranking as $key => $row)
                            <tr>
                                <td>{{ $key }}</td>
                                <td>{{ $row['boardgame_name'] }}</td>
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
