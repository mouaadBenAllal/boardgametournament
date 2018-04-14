@extends('layouts.app')
@section('content')
    <section class="contact-2">
        <div class="container">
            <div class="row contact-details">
                <div class="col-sm-8 m-auto text-center">
                    @include('layouts.error')
                    <h2>Categorie<p style="float: right;"><a href="{{ route('admin/category/create') }}"><button type="button" class="btn btn-secondary">Categorie aanmaken &nbsp;&nbsp;&nbsp;<i class="fa fa-plus" aria-hidden="true"></i></button></a></p></h2>
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
                            <th>Acties</th>
                        </tr>
                        </thead>
                        <tbody>
                        {{--<tr>--}}
                            {{--<td>--}}
                                {{--<a href="{{ route('admin/category/create') }}">Nieuwe categorie aanmaken</a>--}}
                            {{--</td>--}}
                            {{--<td>--}}
                                {{--<a href="{{ route('admin/category/create') }}"><i class="fa fa-plus" aria-hidden="true"></i></a>--}}
                            {{--</td>--}}
                        {{--</tr>--}}
                        @foreach($categories as $key => $category)
                            <tr>
                                <td>{{ $category['name'] }}</td>
                                <td>
                                    <a href="{{ route('admin/category/get', $category->id) }}"><i class="fa fa-list" aria-hidden="true"></i></a>
                                    <a href="{{ route('admin/category/edit', $category->id) }}"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                </td>

                            </tr>

                        @endforeach
                        {{ $categories->links() }}
                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </section>

@endsection
