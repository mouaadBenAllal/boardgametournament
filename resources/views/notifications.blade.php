@extends('layouts.app')

@section('title', "Notifications")

@inject('notifications', 'App\Components\NotificationCollection')

@section('content')
    <div class="container">
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <form method="POST" action="{{route("notifications_post")}}">
                            {{csrf_field()}}
                            Notificaties
                            <button class="btn btn-success float-right">Gelezen</button>
                        </form>
                    </div>
                    <ul class="list-group list-group-flush">
                        @foreach ($notifications->all() as $notification)
                            <li class="list-group-item">
                                <h4>
                                    @if ($notification->read === 0)
                                        <i class="fa fa-envelope" aria-hidden="true"></i>
                                    @endif
                                    {{ $notification->title }}
                                </h4>
                                <p><?= $notification->description ?></p>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
