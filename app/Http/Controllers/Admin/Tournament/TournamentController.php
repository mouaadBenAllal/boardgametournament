<?php

namespace App\Http\Controllers\Admin\Tournament;

use App\Http\Controllers\Controller;
use App\Components\ResponseUtils;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Boardgame;
use App\Models\Tournament;
use App\Components\FlashSession;

class TournamentController extends Controller
{

    /** Constant to display the amount in the pagination */
    const PAGINATION_AMOUNT = 12;
    const TAKE_AMOUNT = 3;

    /**
     * Function to display all tournaments.
     */
    public function index()
    {
        // Define the existing boardgames and add pagination:
        $tournaments = Tournament::withTrashed()->paginate(self::PAGINATION_AMOUNT);
        // Return the view including the categories:
        return view('admin.tournament.index', compact('tournaments'));
    }

    /**
     * Retrieve and return all the existing tournaments to the view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAllTournaments(Request $request)
    {
        if($request->exists('search')){
            if($this->search($request->input('search')) == false){
                FlashSession::addAlert('error', 'Er bestaat geen toernooi: "' . $request->input('search') . '"');
            } else {
                $this->search($request->input('search'));
            }
        }
        // Retrieve all boardgames:
        $tournaments = Tournament::paginate(self::PAGINATION_AMOUNT);
        // Retrieve all categories:
        $categories = Category::all();
        // Return the boardgames to the view:
        return view('admin.tournament.index', compact('tournaments', 'categories'));
    }

    /**
     * Function to view an existing tournament.
     *
     * @param $tournamentId,                The identifier of a tournament.
     */
    public function get($token, Request $request)
    {
        // Define the existing tournament:
        $tournament = Tournament::withTrashed()->where('token', $token)->first();
        // Checks if tournament exists:
        if (!$tournament) {
            // Define an error:
            FlashSession::addAlert('error', 'Er geen toernooi gevonden met dit ID');
            // Return to the overview:
            return redirect()->route('admin/tournament');
        }
        // Define the boardgames:
        $boardgame = $tournament->boardgame()->first();
        // Return to the view:
        return view('admin.tournament.get', compact('tournament', 'boardgame'));
    }

    public function create()
    {
        // Define the boardgames:
        $boardgames = Boardgame::all()->sortBy('name');
        return view('admin.tournament.create', compact('boardgames'));
    }

    public function store(Request $request){
        // Check for validation on form data:
        $this->validate($request, [
            'name' => 'required|max:255',
            'description' => 'nullable',
            'boardgame' => 'required',
            'session_max' => 'required|min:1|numeric',
            'private' => 'boolean',
            'creator' => 'required'
        ]);
        // Define a new tournament
        $tournament = new Tournament;
        $tournament->name = $request->name;
        $tournament->description = $request->description;
        $tournament->session_size = $request->session_max;
        $tournament->boardgame_id = $request->boardgame;
        $tournament->token = substr(md5($request->name. $request->description. $request->session_max. $request->boardgame. $request->boardgame_id. date('Y-m-d H:i:s')), 0,8);
        $tournament->completed = 0;
        $tournament->private = $request->private;
        $tournament->user_id = $request->creator;
        $tournament->save();
        return redirect()->route('admin/tournament');
    }

    /**
     * Function to edit an existing tournament.
     *
     * @param $tournamentId,                The identifier of a tournament.
     */
    public function edit($token)
    {
        // Define the existing tournament:
        $tournament = Tournament::withTrashed()->where('token', $token)->first();
        // Checks for existing tournament:
        if (!$tournament) {
            // Define the flashSession error:
            FlashSession::addAlert('error', 'Er is geen toernooi gevonden met dit ID');
            // Redirect to overview tournaments:
            return redirect()->route('admin/tournament');
        }
        // Define the boardgames:
        $boardgames = Boardgame::all();
        // Return to edit tournament:
        return view('admin.tournament.edit', compact('tournament', 'boardgames'));
    }
    
    /**
     * Update a tournament
     *
     * @param string $token
     * @param Request $request
     * @return void
     */
    public function update($token, Request $request){

        $tournament = Tournament::withTrashed()->where('token', $token)->first();
        $this->validate($request, [
            'name' => 'required',
            'description' => 'nullable',
            'boardgame_id' => 'required',
            'session_size' => 'required|min:1|numeric',
            'private' => 'boolean',
            'creator' => 'required'
        ]);
        $tournament->name = $request->name;
        $tournament->description = $request->description;
        $tournament->session_size = $request->session_size;
        $tournament->boardgame_id = $request->boardgame_id;
        $tournament->completed = 0;
        $tournament->private = $request->private;
        $tournament->user_id = $request->creator;
        // Checks if the tournament is saved:
        if ($tournament->save()) {
            // Add flashSession success:
            FlashSession::addAlert('success', 'Het wijzigen van het toernooi is gelukt');
        } else {
            // Add failure flashSession:
            FlashSession::addAlert('error', 'Het wijzigen vna het toernooi is mislukt');
        }
        // Redirect to tournament overview:
        return redirect()->route('admin/tournament');
    }

    /**
     * Function to delete or restore a trashed tournament.
     * @param   $tournamentId,               The identifier of a tournament.
     */
    public function delete($tournamentId = null, $checkedState = null)
    {
        // Define the id of the tournament:
        $tournamentId = isset($_POST['id']) ? $_POST['id'] : (isset($_GET['id']) ? $_GET['id'] : (!is_null($tournamentId) ? $tournamentId : false));
        // Define the tournament:
        $tournament = Tournament::withTrashed()->where('id', $tournamentId)->first();
        // Check if any tournament is found:
        if(!$tournament){
            // Return an error:
            return ResponseUtils::sendResponse(ResponseUtils::STATUS_NOT_FOUND, array('result' => 'Er is geen tournament gevonden met dit ID'));
        }
        // Define the checked state:
        $checkedState = isset($_POST['checkedState']) ? $_POST['checkedState'] : (isset($_GET['checkedState']) ? $_GET['checkedState'] : (!is_null($checkedState) ? $checkedState : false));
        // Check if the checked state is defined:
        if(!is_null($checkedState)) {
            // Check if the state is true:
            if($checkedState == 'true') {
                // Check if tournament is trashed:
                if ($tournament->trashed()) {
                    // Try to restore the tournament:
                    if (!$tournament->restore()) {
                        // Return an error:
                        return ResponseUtils::sendResponse(ResponseUtils::STATUS_NOT_FOUND, array('result' => 'Er is iets misgegaan bij het terugzetten van de tournament'));
                    }
                }
            } else {
                // Check if tournament is not trashed:
                if (!$tournament->trashed()) {
                    // Try to trash the tournament:
                    if (!$tournament->delete()) {
                        // Return an error:
                        return ResponseUtils::sendResponse(ResponseUtils::STATUS_NOT_FOUND, array('result' => 'Er is iets misgegaan bij het trashen van de tournament'));
                    }
                }
            }
        } else {
            // Return an error:
            return ResponseUtils::sendResponse(ResponseUtils::STATUS_NOT_FOUND, array('result' => 'Er is iets misgegaan bij het ophalen van de status van de checkbox'));
        }
        // Return an success:
        return ResponseUtils::sendResponse(ResponseUtils::STATUS_OK);
    }

    public function destroy(){
        //TODO
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
        $tournaments = Tournament::where('name', 'like', '%' . $search . '%')->get();
        // Check if there are no boardgames found:
        if(!(bool)count($tournaments)){
            // Flash an error:
            return false;
        }
        // Return searched boardgame to the view:
        return view('admin.tournament.index', compact('tournaments'));
    }
}




