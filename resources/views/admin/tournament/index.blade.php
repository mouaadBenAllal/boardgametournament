@extends('layouts.app')
@section('content')
    <section class="contact-2">
        <div class="container">
            <div class="row contact-details">
                <div class="col-sm-8 m-auto text-center">
                    @include('layouts.error')
                    <h2>Toernooien</h2>
                    <div class="divider"></div>
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <table class="table ranking" id="dataTablesTable">
                        <thead>
                        <tr>
                            <th>Naam</th>
                            <th>Tonen</th>
                            <th>Acties</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($tournaments as $tournament)
                            <tr>
                                <td>{{ $tournament->name }}</td>
                                <td>
                                    <input type="checkbox" class="delete_checkbox[{{ $tournament->id }}]" data-toggle="toggle" data-on="Ja" data-off="Nee" data-onstyle="success" data-offstyle="danger" data-size="mini" @php echo !$tournament->trashed() ? 'checked' : '' @endphp onchange="toggleDeletionOn('tournament', {{ $tournament->id }})">
                                </td>
                                <td>
                                    <a href="{{ route('admin/tournament/get', $tournament->token) }}"><i class="fa fa-list" aria-hidden="true"></i></a>
                                    <a href="{{ route('admin/tournament/edit', $tournament->token) }}"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @if($tournaments instanceof \Illuminate\Pagination\LengthAwarePaginator )
                        <nav>
                            {{ $tournaments->links() }}
                        </nav>
                    @endif
                </div>

            </div>
        </div>
    </section>
@endsection

