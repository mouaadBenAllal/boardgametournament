@extends('layouts.app')
@section('content')
    <section class="contact-2">
        <div class="container">
            <div class="row contact-details">
                <div class="col-sm-8 m-auto text-center">
                    @include('layouts.error')
                    <h2>Rol<p style="float: right;"><a href="{{ route('admin/role/create') }}"><button type="button" class="btn btn-secondary">Rol aanmaken &nbsp;&nbsp;&nbsp;<i class="fa fa-plus" aria-hidden="true"></i></button></a></p></h2>
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
                            <th>Authority</th>
                            <th>Acties</th>
                        </tr>
                        </thead>
                        <tbody>
                        {{--<tr>--}}
                            {{--<td>--}}
                                {{--<a href="{{ route('admin/role/create') }}">Nieuwe rol aanmaken</a>--}}
                            {{--</td>--}}
                            {{--<td></td>--}}
                            {{--<td>--}}
                                {{--<a href="{{ route('admin/role/create') }}"><i class="fa fa-plus" aria-hidden="true"></i></a>--}}
                            {{--</td>--}}
                        {{--</tr>--}}
                        @foreach($roles as $key => $role)
                            <tr>
                                <td>{{ $role['name'] }}</td>
                                <td>{{ $role['authority'] }}</td>
                                <td>
                                    <a href="{{ route('admin/role/get', $role->id) }}"><i class="fa fa-list" aria-hidden="true"></i></a>
                                    <a href="{{ route('admin/role/edit', $role->id) }}"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $roles->links() }}
                </div>

            </div>
        </div>
    </section>
@endsection
