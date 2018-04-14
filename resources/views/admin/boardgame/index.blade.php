@extends('layouts.app')
@section('content')
    <section class="contact-2">
        <div class="container">
            <div class="row contact-details">
                <div class="col-sm-8 m-auto text-center">
                    @include('layouts.error')
                    <h2>Bordspel<p style="float: right;"><a href="{{ route('admin/boardgame/create') }}"><button type="button" class="btn btn-secondary">Bordspel aanmaken &nbsp;&nbsp;&nbsp;<i class="fa fa-plus" aria-hidden="true"></i></button></a></p></h2>
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
                            <th>Categorie</th>
                            <th>Tonen</th>
                            <th>Acties</th>
                        </tr>
                        </thead>
                        <tbody>
                        {{--<tr>--}}
                            {{--<td>--}}
                                {{--<a href="{{ route('admin/boardgame/create') }}">Nieuwe boardgame aanmaken</a>--}}
                            {{--</td>--}}
                            {{--<td>--}}
                                {{--<a href="{{ route('admin/boardgame/create') }}"><i class="fa fa-plus" aria-hidden="true"></i></a>--}}
                            {{--</td>--}}
                            {{--<td></td>--}}
                            {{--<td></td>--}}
                        {{--</tr>--}}
                        @foreach($boardgames as $boardgame)
                            <tr>
                                <td>{{ $boardgame->name }}</td>
                                @foreach(\App\Models\Category::all() as $category)
                                    @if($category->id == $boardgame->category_id)
                                        <td>{{ $category->name }}</td>
                                    @endif
                                @endforeach
                                <td>
                                    <input type="checkbox" class="delete_checkbox[{{ $boardgame->id }}]" data-toggle="toggle" data-on="Ja" data-off="Nee" data-onstyle="success" data-offstyle="danger" data-size="mini" @php echo !$boardgame->trashed() ? 'checked' : '' @endphp onchange="toggleDeletionOn('boardgame', {{ $boardgame->id }})">
                                </td>
                                <td>
                                    <a href="{{ route('admin/boardgame/get', $boardgame->id) }}"><i class="fa fa-list" aria-hidden="true"></i></a>
                                    <a href="{{ route('admin/boardgame/edit', $boardgame->id) }}"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>
@endsection

