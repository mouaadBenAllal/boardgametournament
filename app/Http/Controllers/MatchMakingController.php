<?php

namespace App\Http\Controllers;

use App\Components\FlashSession;
use App\Models\SessionHasUser;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Boardgame;
use App\Models\Session;
use App\Models\TournamentHasUser;
use App\Models\Tournament;
use Illuminate\Support\Facades\DB;

class MatchMakingController extends Controller
{

    /**
     *  takes the token from a tournament and constructs sessions for that tournament
     *
     * @param $token
     */
    public function constructSessionsForTournament($token)
    {
        // get the information about a tournament based on the token and
        $tournament = Tournament::where('token', $token)->first();
        // set the id from the tournament for later use
        $tournamentId = $tournament->id;
        //get and set the number of the last round
        $thisRound = Session::getLastRoundNumber($tournamentId);
        //check if there was a last round
        if ($thisRound == 0) {
            $selectedPlayers = [];
            //construct a list of players based on the people who registered for the tournament
            $playerList = TournamentHasUser::getAllTournamentParticipants($tournamentId);
        } else {
            //construct a list of players based on the previous rounds winners
            $selectedPlayers = $this->getWinnersForRound($thisRound, $tournamentId);
            $playerList = TournamentHasUser::getAllTournamentParticipants($tournamentId);
        }
        // define the last round count users:
        $previousSessionsUsersCount = 0;
        // check if there is a last round:
        if($thisRound > 1){
            $previousSessions = \App\Models\Session::where('completed', 0)->where('tournament_id', $tournamentId)->where('round', ($thisRound - 1))->get();
            // Loop trough the previous sessions:
            foreach ($previousSessions as $previousSession) {
                $previousSessionsUsersCount += count($previousSession->sessionHasUser()->get());
            }
        } else {
            $previousSessions = [];
        }
        // define the sessions of this round:
        $thisRoundSessions = Session::where('round', $thisRound)->where('tournament_id', $tournamentId)->get();
        // define the count of users for this round:
        $thisSessionsUsersCount = 0;
        foreach($thisRoundSessions as $thisRoundSession){
            $thisSessionsUsersCount += count($thisRoundSession->sessionHasUser()->get());
        }
        // define the boardgame
        $boardgame = Boardgame::where('id', $tournament->boardgame_id)->first();
        // check if tournament is completed
        if(count($thisRoundSessions) == count($selectedPlayers) && $thisRound != 0 && count($selectedPlayers) == round(($thisSessionsUsersCount / $tournament->session_size)) && count($selectedPlayers) < $boardgame->min_players){
            // set tournament on completed
            $tournament->completed = 1;
            // save it
            $tournament->save();
            // set all previous sessions on complete:
            foreach ($thisRoundSessions as $thisRoundSession) {
                $thisRoundSession->completed = 1;
                $thisRoundSession->save();
            }
            $winnersArray = [];
            if(count($thisRoundSessions) > 0 && $tournament->completed == 1){
                foreach($thisRoundSessions as $thisRoundSession) {
                    $winners = $thisRoundSession->sessionHasUser()->where('result', 1)->get();
                    // Loop trough the winners:
                    foreach($winners as $winner) {
                        $winnersArray[] = $winner->user()->first()->username;
                    }
                }
            }
            FlashSession::addAlert('success', 'Het toernooi is afgelopen, de winnaar(s) is/zijn: "' . implode(', ', $winnersArray) . '".');
        } else {
            if($thisRound != 0) {
                if (count($thisRoundSessions) != count($selectedPlayers) || count($selectedPlayers) == 0 || count($selectedPlayers) < ceil( $thisSessionsUsersCount == 0 ?
                        $playerList : $thisSessionsUsersCount / $tournament->session_size)) {
                    FlashSession::addAlert('error', 'Je hebt nog niet alle winnaar(s) van de laatste ronde gekozen.');
                    return back();
                }
            }
            //construct and save a list of sessions based on the list of players
            $sessionList = $this->constructSessionsFromPlayerList($tournamentId, (count($selectedPlayers) == 0 ? $playerList : $selectedPlayers));
            if(count($previousSessions) != 0){
                // set all previous sessions on complete:
                foreach ($previousSessions as $previousSession) {
                    $previousSession->completed = 1;
                    $previousSession->save();
                }
            }
            //save the sessions to the database
            Session::saveSessionToDatabase($sessionList, $tournamentId);
        }
        return back();
    }

    /**
     * construct sessions based for the given tournament based on the playerlist given
     *
     * @param $tournamentId
     * @param $playerList
     * @return array
     */
    public function constructTournamentOutput($token, $location = 'started')
    {
        $tournament = Tournament::where('token', $token)->first();
        if (!$tournament instanceof Tournament) {
            return false;
        }
        $tournamentId = $tournament->id;
        $previousRound = Session::getLastRoundNumber($tournamentId);

        if ($previousRound === 0) {
            return false;
        }

        $rounds = [];
        for ($roundNumber = 1; $roundNumber <= $previousRound; $roundNumber++) {
            $rounds[$roundNumber] = [];

            /** @var Session[] $sessions */
            $sessions = Session::where("tournament_id", $tournamentId)->where("round", $roundNumber)->get()->all();

            /** @var Session $session */
            foreach ($sessions as $session) {
                /** @var SessionHasUser[] $sessionUsers */
                $sessionHasUsers = $session->sessionHasUser()->get()->all();

                $sessionUserList = [];
                /** @var SessionHasUser $sessionHasUser */
                foreach ($sessionHasUsers as $sessionHasUser) {
                    /** @var User $user */
                    $user = $sessionHasUser->user()->get()->first();
                    if ($user instanceof User) {
                        $sessionUserList[] = ["info" => $user, "result" => $sessionHasUser->result];
                    }
                }

                // add the user list to the session within the current round
                $rounds[$roundNumber][$session->id] = [
                    "users" => $sessionUserList,
                    "session" => $session
                ];
            }

        }
        return $rounds;
    }

    public function constructSessionsFromPlayerList($tournamentId, $playerList)
    {
        // fetch the size of the groups the games will be played in
        $SessionSize = Session::getSessionSize($tournamentId);

        //randomize the player list
        shuffle($playerList);

        //divide the players into sessions
        $listOfSessions = array_chunk($playerList, $SessionSize, false);

        return $listOfSessions;
    }

    /**
     * returns a list of players who won the round of the given round number
     * @param $lastRound
     * @param $tournamentId
     * @return mixed
     */
    public function getWinnersForRound($lastRound, $tournamentId)
    {
        $winners = DB::table('sessions')
            ->join('session_has_user', 'sessions.id', '=', 'session_has_user.session_id')
            ->select('user_id')
            ->where([
                ['result', '=', '1'],
                ['tournament_id', '=', $tournamentId],
                ['round', '=', $lastRound]
            ])
            ->get();

        return $winners->toArray();
    }


}
