<?php

namespace App\Http\Controllers\Boardgame;

use App\Facades\RankingFacade;
use App\Http\Controllers\Controller;
use App\Models\Boardgame;
use Illuminate\Http\Request;
use Validator;

/**
 * Class to handle everything for display of the ranking.
 * @package App\Http\Controllers,              Extending the package controller.
 */
class RankingController extends Controller
{
    /**
     * Function to show the overview of the ranking for the specific boardgame.
     * @param $boardgameId,                 The identifier of the boardgame.
     * @return \Illuminate\Http\Response,   The view of the ranking.
     */
    public function index($boardgameId = null)
    {
        // Define the id of the boardgame:
        $boardgameId = isset($_POST['id']) ? $_POST['id'] : (isset($_GET['id']) ? $_GET['id'] : (!is_null($boardgameId) ? $boardgameId : false));
        // Define the specific boardgame:
        $boardgame = Boardgame::where('id', $boardgameId)->first();
        // Check if the boardgame is found:
        if($boardgame) {
            // Define the facade for the ranking:
            $rankingFacade = new RankingFacade();
            // Get the ranking for the boardgame:
            $ranking = $rankingFacade->boardgame($boardgame->id);
            // Check if the ranking is found:
            if(is_array($ranking)){
                // Render the view:
                return view('boardgame.ranking', compact(array('ranking', 'boardgame')));
            } else {
                // Display an error:
//                $validator->getMessageBag()->add('boardgame', 'Er is geen boardgame gevonden met het ID: "' . $boardgameId . '"');
            }
        } else {
            // Display an error:
//                $validator->getMessageBag()->add('boardgame', 'Er is geen boardgame gevonden met het ID: "' . $boardgameId . '"');
        }
        // Return to the home:
        return redirect('/');
    }
}
