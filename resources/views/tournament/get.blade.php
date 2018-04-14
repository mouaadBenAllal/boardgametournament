@extends('layouts.app')
@section('title')
    Toernooi - {{$tournament->name}}
@endsection
@section('content')
    <div class="container mt-3">
        @include('layouts.error')
        <div class="card">
            <div class="card-body">
                <h3>{{$tournament->name}} @if($tournament->private == 1) <span class="badge badge-pill badge-secondary">Privé</span> @endif</h3>
                <p>Gemaakt door: <strong>{{$tournament->user()->get()->first()->username}}</strong></p>
                <p>Bordspel: <a href="/boardgame/get/{{ $boardgame->id }}"><strong>{{$boardgame->name}}</strong></a></p>
                @if(!empty($tournament->description))
                    <p>Beschrijving: <strong>{{$tournament->description}}</strong></p>
                @endif
                <p>Sessie grootte: <strong>{{$tournament->session_size}}</strong></p>
                <?php
                $lastRoundList = \App\Models\Session::where('tournament_id', $tournament->id)->where('completed', 1)->orderBy('round', 'DESC')->first();
                if(count($lastRoundList)){
                    $lastRounds = \App\Models\Session::where('tournament_id', $tournament->id)->where('completed', 1)->where('round', $lastRoundList->round)->get();
                } else {
                    $lastRounds = [];
                }
                $winnersArray = [];
                if(count($lastRounds) > 0 && $tournament->completed == 1){
                    foreach($lastRounds as $lastRound) {
                        $winners = $lastRound->sessionHasUser()->where('result', 1)->get();
                        // Loop trough the winners:
                        foreach($winners as $winner) {
                            $winnersArray[] = $winner->user()->first()->username;
                        }
                    }
                }
                ?>
                @if(count($winnersArray) > 0)
                    <p>Winnaar(s): <strong>{{ implode(', ', $winnersArray)}}</strong></p>
                @endif
                @Auth
                    <div id="tournament_button_div">
                        @if ($tournament->user()->get()->first()->username == Auth::user()->username && $tournament->started == 0)
                            <a href="/tournament/start/{{$tournament->token}}" class="btn btn-success"
                               onclick="return confirm('Wilt u dit toernooi starten?')">Start toernooi</a>
                            <a href="/tournament/delete/{{$tournament->token}}" class="btn btn-danger"
                               value="Toernooi annuleren" onclick="return confirm('Weet u zeker dat u dit toernooi verwijderen?')">Verwijder
                                toernooi</a>
                            <a href="/tournament/edit/{{$tournament->token}}" class="btn btn-warning"
                               value="Toernooi annuleren">Aanpassen toernooi</a>
                            @if(Auth::user() && $tournament->user()->get()->first()->id == Auth::id())
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#inviteModal">Personen uitnodigen</button>
                                <div class="modal fade" id="inviteModal" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title">Personen uitnodigen</h4>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route("tournament_invite", ["token" => $tournament->token]) }}"
                                                      method="POST">
                                                    {{ csrf_field() }}
                                                    <select class="form-control" name="user_id">
                                                        @foreach($users as $user)
                                                            @if(Auth::user()->id !== $user->id)
                                                                <option value="{{$user->id}}">
                                                                    {{$user->username}}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                        <input name="tournament_id" value="<?= $tournament->id ?>" hidden/>
                                                    </select>
                                                    <br>
                                                    <button style="float: right;"
                                                            class="btn btn-default">
                                                        Verzend
                                                    </button>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Sluiten</button>
                                            </div>
                                        </div>
                                        @endif

                                    </div>
                                </div>
                            @endif
                            @if ($tournament->completed != 1)
                                @if($tournament->started == 0)
                                    @if (count(App\Models\TournamentHasUser::where('tournament_id', $tournament->id)->where('user_id', Auth::id())->get()) == 0)
                                        <input name="tournament_button" id="tournament_button" type="button"
                                               data-username="{{Auth::user()->username}}" data-user_id="{{Auth::id()}}"
                                               data-tournament-id="{{$tournament->id}}" data-img="{{Auth::user()->image}}"
                                               style="display: none; margin: 0;" value="1" onclick="toggleJoinTournament(this)"/>
                                        <label for="tournament_button" id="tournament_button-label" class="btn btn-success">
                                            Meedoen aan toernooi
                                        </label>
                                    @else
                                        @if ($tournament->started == 0)
                                            <input name="tournament_button" id="tournament_button" type="button"
                                                   data-username="{{Auth::user()->username}}" data-user_id="{{Auth::id()}}"
                                                   data-tournament-id="{{$tournament->id}}" style=" display: none; margin: 0;" value="0"
                                                   onclick="toggleJoinTournament(this)"/>
                                            <label for="tournament_button" id="tournament_button-label" class="btn btn-danger">
                                                Niet meer meedoen
                                            </label>
                                        @endif
                                    @endif
                                @else
                                    @if ($tournament->started == 1)
                                        @if(count(App\Models\TournamentHasUser::where('tournament_id', $tournament->id)->where('user_id', Auth::id())->get()) != 1 && count(App\Models\Tournament::where(array('user_id' => Auth::id(), 'id' => $tournament->id))->get()) != 1)
                                            <p><strong>Dit toernooi is bezig, u kunt niet deelnemen aan dit toernooi.</strong></p>
                                        @else
                                            @if(count(App\Models\Tournament::where(array('user_id' => Auth::id(), 'id' => $tournament->id))->get()) == 1)
                                                <?php
                                                    $thisRoundNumber = \App\Models\Session::getLastRoundNumber($tournament->id);
                                                    $thisRounds = \App\Models\Session::where('completed', 0)->where('tournament_id', $tournament->id)->where('round', $thisRoundNumber)->get();
                                                    $thisCountUser = 0;
                                                    foreach ($thisRounds as $thisRound) {
                                                        $thisCountUser += count($thisRound->sessionHasUser()->get());
                                                    }
                                                    $last = false;
                                                    if(ceil(($thisCountUser / $tournament->session_size)) < $tournament->boardgame()->first()->min_players){
                                                        $last = true;
                                                    }
                                                    $messageForNextButton = $last == true ? 'Weet je zeker dat je het toernooi wilt beëindigen?' : 'Weet je zeker dat je de volgende ronde wil starten?';
                                                ?>
                                                <a href="/tournament/started/{{$tournament->token}}"
                                                   class="btn btn-success"
                                                   onclick="return confirm('<?= $messageForNextButton ?>')">
                                                    @if($last == true)
                                                        Beëindig het toernooi
                                                    @else
                                                        Volgende ronde
                                                    @endif
                                                </a>
                                            @endif
                                        @endif
                                    @else
                                        <p><strong>Dit toernooi zit vol, u kunt niet deelnemen aan dit toernooi</strong></p>
                                    @endif
                                @endif
                            @endif
                    </div>
                @endauth
                @guest
                    <p>U moet <a href="/login">inloggen</a> of <a href="/register">registreren</a> om deel te nemen aan
                        dit toernooi</p>
                @endguest
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h4>Toernooi</h4>
                    </div>
                    <div class="card-body">

                        @if($tournamentRounds !== false)
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                @foreach($tournamentRounds as $roundId => $sessions)
                                    <li class="@if($loop->last) active @endif">
                                        <a href="#round{{$roundId}}" data-toggle="tab">Ronde {{$roundId}}</a>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                @foreach($tournamentRounds as $roundId => $sessions)
                                    <div class="tab-pane @if($loop->last) active @endif"
                                         aria-expanded="@if($loop->last) true @endif"
                                         id="round{{$roundId}}" role="tabpanel"
                                         aria-labelledby="round{{$roundId}}-tab">
                                            <div class="table-responsive">
                                            <table class="table">
                                                <tbody>
                                                @if($loop->last)
                                                    {{-- final round so winners can be selected/changed --}}
                                                    @foreach($sessions as $sessionId => $session)
                                                        <tr>
                                                            @foreach($session["users"] as $user)
                                                                @if($user["result"] == 1)
                                                                    <td class="table-success text-center">
                                                                        {{$user["info"]->username}}
                                                                    </td>
                                                                @else
                                                                    @if(Auth::user() && $tournament->user()->get()->first()->username == Auth::user()->username && $tournament->started == 1 && $tournament->completed == 0)
                                                                        <td class="text-center">
                                                                            <form action="{{ route("tournament_declare_winner", ["token" => $tournament->token]) }}"
                                                                                  method="POST">
                                                                                {{ csrf_field() }}
                                                                                <input type="hidden" name="session_id"
                                                                                       value="{{ $sessionId  }}"/>
                                                                                <input type="hidden" name="user_id"
                                                                                       value="{{ $user["info"]->id }}"/>

                                                                                <button class="btn btn-default">
                                                                                    {{$user["info"]->username}}
                                                                                </button>
                                                                            </form>
                                                                        </td>
                                                                    @else
                                                                        <td class="text-center">
                                                                            {{$user["info"]->username}}
                                                                        </td>
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    {{-- not the final round --}}
                                                    @foreach($sessions as $session)
                                                        <tr>
                                                            @foreach($session["users"] as $user)
                                                                <td class="@if($user["result"] == 1) table-primary @endif">
                                                                    {{$user["info"]->username}}
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h4>Spelers <span id="tournament_player_count"
                                          class="badge badge-pill badge-secondary">{{count($players)}}</span></h4>
                    </div>
                    <ul id="tournament_players" class="list-group list-group-flush">
                        @foreach($players as $player)
                            <li id="tournament_player[{{$player->id}}]"
                                class="list-group-item">
                                <a href="/user/get/<?= $player->username ?>">{{$player->username}}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
