<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Invite;
use App\Models\Session;
use App\Models\SessionHasUser;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Boardgame;
use App\Models\Tournament;
use App\Components\FlashSession;
use App\Models\TournamentHasUser;
use Illuminate\Support\Facades\Auth;
use App\Components\ResponseUtils;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;


class TournamentController extends Controller
{

    /** Constant to display the amount in the pagination */
    const PAGINATION_AMOUNT = 12;
    const TAKE_AMOUNT = 3;

    /**
     * Retrieve and return all the existing tournaments to the view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAllTournaments(Request $request)
    {
        // Retrieve all tournaments:
        $tournaments = Tournament::where('private', 0)->paginate(self::PAGINATION_AMOUNT);
        if ($request->exists('search')) {
            if ($this->search($request->input('search')) == false) {
                FlashSession::addAlert('error', 'Er bestaat geen toernooi: "'.$request->input('search').'"');
            } else {
                $tournaments = $this->search($request->input('search'));
            }
        }
        // Retrieve all categories:
        $boardgames = Boardgame::all();
        // Return the data to the view:
        return view('tournament.index', compact('tournaments', 'boardgames'));
    }

    /**
     * Function that will retrieve only the tournaments
     * created by logged in user
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getMyTournaments(Request $request) {
        // Retrieve all tournaments:
        $tournaments = Tournament::where('user_id', Auth::id())->paginate(self::PAGINATION_AMOUNT);
        if ($request->exists('search')) {
            if ($this->search($request->input('search')) == false) {
                FlashSession::addAlert('error', 'Er bestaat geen eigen toernooi: "'.$request->input('search').'"');
            } else {
                $tournaments = $this->search($request->input('search'));
            }
        }
        // Retrieve all categories:
        $boardgames = Boardgame::all();
        // Return the data to the view:
        return view('tournament.own', compact('tournaments', 'boardgames'));
    }

    /**
     * Function to view an existing tournament.
     * @param $tournamentId ,                The identifier of a tournament.
     */
    public function get($token, Request $request)
    {
        $tournament = Tournament::where('token', $token)->get()->first();
        if($tournament->private == 1){
            if(Auth::id() != $tournament->user_id) {
                if (count(Invite::where('user_id', Auth::id())->where('tournament_id', $tournament->id)->get()) == 0) {
                    return redirect('/tournament');
                }
            }
        }
        $users = User::all();

        if (!$tournament) {
            // Display an error:
            FlashSession::addAlert('error', 'Er is geen tournament gevonden met dit ID');
            // Return to the overview:
            return redirect('/');
        }
        $boardgame = $tournament->boardgame()->get()->first();
        $tournamentHasUserObjects = TournamentHasUser::where('tournament_id', $tournament->id)->get();
        $players = array();
        // Loop trough all the objects:
        foreach ($tournamentHasUserObjects as $tournamentHasUserObject) {
            $players[] = $tournamentHasUserObject->user()->get()->first();
        }

        // get all the rounds for this tournament
        $matchMakingController = new MatchMakingController();
        $tournamentRounds = $matchMakingController->constructTournamentOutput($token, 'get');

        return view('tournament.get', compact('tournament', 'boardgame', 'players', 'tournamentRounds', 'users'));

    }

    /**
     * Show the form to create a new tournament
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Retrieve the boardagmes:
        $boardgames = Boardgame::all()->sortBy('name');
        // Check for boardgames:
        if(count($boardgames) == 0) {
            // Define flashsession:
            FlashSession::addAlert('warning', 'Er zijn geen bordspellen aanwezig, neem contact op met de beheerder');
        }
        return view('tournament.create', compact('boardgames'));
    }

    /**
     * Stores a new created tournament
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'description' => 'nullable',
            'boardgame' => 'required',
            'session_max' => 'required|min:1|numeric',
            'creator' => 'required'
        ]);
        // Define the boardgame:
        $boardgame = Boardgame::where('id', $request->boardgame)->first();
        if($request->session_max < $boardgame->min_players || $request->session_max > $boardgame->max_players){
            FlashSession::addAlert('error', 'Deze boardgame vereist minimaal een "sessie grootte" tussen de ' . $boardgame->min_players .' en ' . $boardgame->max_players .' spelers');
            return redirect()->back()->withInput(Input::all());
        }
        $tournament = new Tournament;
        $token = substr(md5($request->name.$request->description.$request->session_max.$request->boardgame.$request->boardgame_id.date('Y-m-d H:i:s')),
            0, 8);
        $tournament->name = $request->name;
        $tournament->description = $request->description;
        $tournament->session_size = $request->session_max;
        $tournament->boardgame_id = $request->boardgame;
        $tournament->token = $token;
        $tournament->completed = 0;
        $tournament->private = $request->private;
        $tournament->user_id = $request->creator;
        $tournament->save();
        return redirect('/tournament/get/'.$token);
    }

    /**
     * Function to edit an existing tournament.
     * @param $tournamentId ,                The identifier of a tournament.
     */
    public function edit($token, Request $request)
    {
        $tournament = Tournament::where('token', $token)->get()->first();
        if (!$tournament) {
            // Display an error:
            FlashSession::addAlert('error','Er is geen tournament gevonden met dit ID');
            // Return to the overview:
            return redirect('/');
        }
        $boardgames = Boardgame::all();
        return view('tournament.edit', compact('tournament', 'boardgames'));
    }

    /**
     * Retrieve all the records with the same name
     *
     * @param   Request $request
     * @return  \Illuminate\Http\Response
     */
    public function search($search)
    {
        // Define the request:
        $request = app(\Illuminate\Http\Request::class);
        // Retrieve the boardgame object:
        $tournaments = Tournament::where('name', 'like', '%'.$search.'%')->get();
        // Check if there are no boardgames found:
        if (!(bool)count($tournaments)) {
            // Flash an error:
            return false;
        }
        // Return searched boardgame to the view:
        return $tournaments;
    }

    /**
     * function to join a tournamet
     *
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function join()
    {
        $tournamentId = isset($_POST['id']) ? $_POST['id'] : (isset($_GET['id']) ? $_GET['id'] : false);
        $checkedState = isset($_POST['checkedState']) ? $_POST['checkedState'] : (isset($_GET['checkedState']) ? $_GET['checkedState'] : false);
        if (!$tournamentId && !$checkedState) {
            return ResponseUtils::sendResponse(ResponseUtils::STATUS_NOT_FOUND,
                'Geen tournament id of checkedstate error');
        }
        //meedoen
        $tournamentHasUserObjects = TournamentHasUser::where('user_id', Auth::id())->where('tournament_id',
            $tournamentId)->get();
        $tournament = Tournament::where('id', $tournamentId)->first();

        if (!$tournament) {
            return ResponseUtils::sendResponse(ResponseUtils::STATUS_NOT_FOUND, 'Tournament niet gevonden error');
        }
        if (!Auth::id()) {
            return ResponseUtils::sendResponse(ResponseUtils::STATUS_NOT_FOUND, 'Niet ingelogd error');

        }
        $currentAmountOfPlayers = TournamentHasUser::where('tournament_id', $tournamentId)->get();
        if ($checkedState == 1) {
            if ($tournamentHasUserObjects->count() == 0) {
                // Define the object for the new tournament has user:
                    $tournamentUser = new TournamentHasUser();
                    $tournamentUser->user_id = Auth::id();
                    $tournamentUser->tournament_id = $tournament->id;
                    // try to create:
                    if (!$tournamentUser->save()) {
                        return ResponseUtils::sendResponse(ResponseUtils::STATUS_NOT_FOUND,
                            'Het aanmaken van het object is mislukt');
                    }
            }
        } else {
            if (!TournamentHasUser::where('user_id', Auth::id())->where('tournament_id', $tournament->id)->delete()) {
                return ResponseUtils::sendResponse(ResponseUtils::STATUS_NOT_FOUND, 'Verwijderen ging fout');
            }
        }
        return ResponseUtils::sendResponse(ResponseUtils::STATUS_OK);
    }

    /**
     * function to softdelete tournament
     *
     * @param String $token
     * @return void
     */
    public function delete($token)
    {
        $tournamentBuilder = Tournament::where('token', $token);

        // get all users who are part of the tournament
        $tournamentInfo = $tournamentBuilder->get()->first();
        $tournamentUsers = $tournamentInfo->tournamentHasUser->all();

        $notificationData = [];
        foreach ($tournamentUsers as $tournamentUser) {
            $notificationData[] = [
                "title" => "Een toernooi waar u aan deelneemt is verwijderd!",
                "description" => "Een toernooi waar u aan deelneemt is verwijderd!",
                "user_id" => $tournamentUser->user_id,
                "read" => 0
            ];
        }

        // add the notifications
        DB::table('notifications')->insert($notificationData);

        // soft delete the tournament now
        $tournamentBuilder->delete();

        return redirect('/');
    }
/**
 * Function to start the tournament
 *
 * @param String $token
 * @return void
 */
    public function start($token){
        // get the information about a tournament based on the token and
        $tournament = Tournament::where('token', $token)->first();
        // set the id from the tournament for later use
        $tournamentId = $tournament->id;
        //get tournament participants list
        $players = TournamentHasUser::getAllTournamentParticipants($tournamentId);
        // Define the boardgame:
        $boardgame = Boardgame::where('id', $tournament->boardgame_id)->first();
        if(count($players) < $boardgame->min_players){
            FlashSession::addAlert('error', 'Je moet meer dan ' . $boardgame->min_players .' spelers hebben om dit toernooi te starten');
            return back();
        }
        $tournament = Tournament::where('token', $token)->first();
        $tournament->started = true;
        $tournament->save();

        $matchMakingController = new MatchMakingController();

        // generate the sessions for the tournament
        $matchMakingController->constructSessionsForTournament($token);

        return back();
    }

    /**
     * Function to start the tournament
     *
     * @param String $token
     * @return void
     */
    public function nextRound($token)
    {
        $matchMakingController = new MatchMakingController();
        $matchMakingController->constructSessionsForTournament($token);
        return back();
    }



    public function invite(Request $request,$token)
    {
        $tournament = Tournament::where('token', $token)->get()->first();
        if (!Auth::user() && !$tournament->user()->get()->first()->username == Auth::user()->username) {
            return back();
        }

        $this->validate($request, [
            'user_id' => 'required',
            'tournament_id' => 'required'
        ]);

        // get the target user's info
        $userInfo = User::where('id', $request->user_id)->get()->first();
        if(!$userInfo instanceof User){
            return back();
        }

        // generate a list of user ids so we can check more easily
        $tournamentUsersList = [];
        $tournamentHasUsers = TournamentHasUser::where('tournament_id', $tournament->id)->get()->all();
        foreach($tournamentHasUsers as $tournamentHasUser){
            $tournamentUsersList[] = $tournamentHasUser->id;
        }

        if(in_array(intval($request->user_id), $tournamentUsersList)){
            // already in the tournament
            return back();
        }

        if(count(Invite::where(  "user_id", $request->user_id)->where("tournament_id",$request->tournament_id)->first()) == 0) {
            $description = "Je kan het toernooi <a href='" . route("/tournament/get", ["token" => $tournament->token]) . "'>hier</a> bekijken.";

            // add the notification
            DB::table('notifications')->insert([
                "title" => "Je bent uitgenodigd voor het toernooi: '" . $tournament->name ."'!",
                "description" => $description,
                "user_id" => $request->user_id,
                "read" => 0
            ]);
            // add the invite
            DB::table('invites')->insert([
                "user_id" => $request->user_id,
                "tournament_id" => $request->tournament_id,
            ]);

        }
        FlashSession::addAlert('success', 'Uitnodiging is verzonden naar "' . $userInfo->username . '"');
        return back();
    }

    public function declareWinner(Request $request, $token)
    {
        $tournament = Tournament::where('token', $token)->get()->first();

        if (!Auth::user() && !$tournament->user()->get()->first()->username == Auth::user()->username) {
            return back();
        }

        $this->validate($request, [
            'session_id' => 'required',
            'user_id' => 'required'
        ]);

        // set all users for this session to 0
        SessionHasUser::where("result", 1)
            ->where("session_id", $request->session_id)
            ->update(["result" => 0]);

        // set the winner for this session to 1
        SessionHasUser::where("session_id", $request->session_id)
            ->where("user_id", $request->user_id)
            ->update(["result" => 1]);

        return back();
    }

    public function update(Request $request, $token)
    {
        $tournament = Tournament::where('token', $token)->get()->first();
        $this->validate($request, [
            'name' => 'required|max:255',
            'description' => 'nullable',
            'boardgame' => 'required',
            'session_max' => 'required|min:1|numeric',
            'private' => 'boolean',
            'creator' => 'required'
        ]);
        // Define the boardgame:
        $boardgame = Boardgame::where('id', $request->boardgame)->first();
        if($request->session_max < $boardgame->min_players || $request->session_max > $boardgame->max_players){
            FlashSession::addAlert('error', 'Deze boardgame vereist minimaal een "sessie grootte" tussen de ' . $boardgame->min_players .' en ' . $boardgame->max_players .' spelers');
            return redirect()->back()->withInput(Input::all());
        }
        if($tournament->private == 0) {
            if($request->private == 1) {
                if (count($tournament->tournamentHasUser()->get()) > 0) {
                    foreach ($tournament->tournamentHasUser()->get() as $tournamentHasUser) {
                        $user = User::where('id', $tournamentHasUser->user_id)->first();
                        if (count(Invite::where('user_id', $user->id)->where('tournament_id', $tournament->id)->first()) == 0) {
                            $invite = new Invite();
                            $invite->user_id = $user->id;
                            $invite->tournament_id = $tournament->id;
                            $invite->save();
                        }
                    }
                }
            }
        } else {
            $invites = Invite::where('tournament_id', $tournament->id)->get();
            foreach($invites as $invite){
                $invite->delete();
            }
        }
        $tournament->name = $request->name;
        $tournament->description = $request->description;
        $tournament->session_size = $request->session_max;
        $tournament->boardgame_id = $request->boardgame;
        $tournament->completed = 0;
        $tournament->private = $request->private;
        $tournament->user_id = $request->creator;
        $tournament->save();
        return redirect('/tournament/get/'.$token);
    }
}

