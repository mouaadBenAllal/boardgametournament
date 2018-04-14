<?php

namespace App\Http\Controllers\Admin\Boardgame;

use App\Components\FlashSession;
use App\Components\ResponseUtils;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\Boardgame;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;

/**
 * Class for handling everything for the boardgame within the CMS.
 */
class BoardgameController extends Controller
{

    /** Constant to display the amount in the pagination */
    const PAGINATION_AMOUNT = 8;

    /**
     * Function to display the existing boardgames.
     */
    public function index()
    {
        // Define the existing boardgames and add pagination:
        $boardgames = Boardgame::withTrashed()->get();
        // Return the view including the categories:
        return view('admin.boardgame.index', compact('boardgames'));
    }

    /**
     * Function to create a new boardgame by the filled form-data or display the form.
     */
    public function create()
    {
        // Return the view:
        return view('admin.boardgame.create');
    }

    public function store(Request $request){

        $data = $this->validate($request, [
            'name' => 'required',
            'description' => 'nullable',
            'min_players' => 'numeric|min:1|required',
            'max_players' => 'numeric|required',
            'avg_time' => 'required',
            'deleted_at' => 'nullable',
            'category_id' => 'required',
            'image' => 'required|image'
        ]);
        // Define the boardgame:
        $boardgame = Boardgame::where('name', $request['name'])->first();
        // Check if any boardgame is found:
        if ($boardgame) {
            // Display an error:
            FlashSession::addAlert('error','Er is al een boardgame met deze naam');
            // Return to the overview:
            return redirect()->back()->withInput(Input::all());
        }
        // Assign form data to the database:
        $boardgame = Boardgame::create($data);
        // Check if the creation of the boardgame succeeded:
        if (!$boardgame) {
            // Display an error:
            FlashSession::addAlert('error', 'Het aanmaken van de boardgame is mislukt');
        } else {
            // Display an message:
            FlashSession::addAlert('success', 'Het aanmaken van de boardgame is gelukt');
        }
        // Return to the overview:
        return redirect('/admin/boardgame');
    }

    /**
     * Function to display the data of a boardgame.
     * @param $boardgameId,                      The identifier of a boardgame.
     */
    public function get($boardgameId = null)
    {
        // Define the id of the boardgame:
        $boardgameId = isset($_POST['id']) ? $_POST['id'] : (isset($_GET['id']) ? $_GET['id'] : (!is_null($boardgameId) ? $boardgameId : false));
        // Define the boardgame:
        $boardgame = Boardgame::withTrashed()->where('id', $boardgameId)->first();
        // Check if any boardgame is found:
        if(!$boardgame){
            // Display an error:
            FlashSession::addAlert('error', 'Er geen boardgame gevonden met dit ID');
            // Return to the overview:
            return redirect('/admin/boardgame');
        }
        // Return to view with boardgame data:
        return view('admin.boardgame.get', compact('boardgame'));
    }

    /**
     * Function to edit an existing boardgame.
     * @param $boardgameId,                      The identifier of a boardgame.
     */
    public function edit($boardgameId)
    {
        // Define the boardgame:
        $boardgame = Boardgame::withTrashed()->where('id', $boardgameId)->first();
        // Check if any boardgame is found:
        if(!$boardgame){
            // Display an error:
            FlashSession::addAlert('error', 'Er geen boardgame gevonden met dit ID');
            // Return to the overview:
            return redirect('/admin/boardgame');
        }
        // Check if the form is posted:
        // Return to the view with boardgames data:
        return view('admin.boardgame.edit', compact('boardgame'));
    }

    public function save($boardgameId, Request $request){

        $boardgame = Boardgame::withTrashed()->where('id', $boardgameId)->first();
        $this->validate($request, [
            'name' => 'required',
            'description' => 'nullable',
            'min_players' => 'numeric|min:1|required',
            'max_players' => 'numeric|required',
            'avg_time' => 'required',
            'deleted_at' => 'nullable',
            'category_id' => 'required',
            'image' => 'image'
        ]);
        // Assign the request variables to the boardgame:
        $boardgame->name = $request->name;
        $boardgame->description = $request->description;
        $boardgame->min_players = $request->min_players;
        $boardgame->max_players = $request->max_players;
        $boardgame->avg_time = $request->avg_time;
        $boardgame->created_at = $request->created_at;
        $boardgame->category_id = $request->category_id;
        // Check if the request has a file:
        if($request->hasFile('image')){
            // Define the image:
            $boardgame->image = 'data:' . $request->file('image')->getMimeType() . ';base64,' . base64_encode(file_get_contents($request->file('image')->path()));
        }
        // Check if edit of the boardgame succeeded:
        if ($boardgame->save()) {
            // Display an error:
            FlashSession::addAlert('success', 'Het aanpassen van de boardgame is gelukt');
        } else {
            // Display an message:
            FlashSession::addAlert('error', 'Het aanpassen van de boardgame is mislukt');
        }
        // Redirect to view:
        return redirect('/admin/boardgame');
    }

    /**
     * Function to delete or restore a trashed boardgame.
     * @param   $boardgameId,               The identifier of a boardgame.
     */
    public function delete($boardgameId = null, $checkedState = null)
    {
        // Define the id of the boardgame:
        $boardgameId = isset($_POST['id']) ? $_POST['id'] : (isset($_GET['id']) ? $_GET['id'] : (!is_null($boardgameId) ? $boardgameId : false));
        // Define the boardgame:
        $boardgame = Boardgame::withTrashed()->where('id', $boardgameId)->first();
        // Check if any boardgame is found:
        if(!$boardgame){
            // Return an error:
            return ResponseUtils::sendResponse(ResponseUtils::STATUS_NOT_FOUND, array('result' => 'Er is geen boardgame gevonden met dit ID'));
        }
        // Define the checked state:
        $checkedState = isset($_POST['checkedState']) ? $_POST['checkedState'] : (isset($_GET['checkedState']) ? $_GET['checkedState'] : (!is_null($checkedState) ? $checkedState : false));
        // Check if the checked state is defined:
        if(!is_null($checkedState)) {
            // Check if the state is true:
            if($checkedState == 'true') {
                // Check if boardgames is trashed:
                if ($boardgame->trashed()) {
                    // Try to restore the boardgame:
                    if (!$boardgame->restore()) {
                        // Return an error:
                        return ResponseUtils::sendResponse(ResponseUtils::STATUS_NOT_FOUND, array('result' => 'Er is iets misgegaan bij het terugzetten van de boardgame'));
                    }
                }
            } else {
                // Check if boardgames is not trashed:
                if (!$boardgame->trashed()) {
                    // Try to trash the boardgame:
                    if (!$boardgame->delete()) {
                        // Return an error:
                        return ResponseUtils::sendResponse(ResponseUtils::STATUS_NOT_FOUND, array('result' => 'Er is iets misgegaan bij het trashen van de boardgame'));
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

    public function destroy(Request $request){
        //TODO
    }
}
