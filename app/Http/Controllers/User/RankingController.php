<?php

namespace App\Http\Controllers\User;

use App\Facades\RankingFacade;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;

/**
 * Class to handle everything for display of the ranking.
 * @package App\Http\Controllers,              Extending the package controller.
 */
class RankingController extends Controller
{
    /**
     * Function to show the overview of the ranking for the specific user.
     * @param $userId,                      The identifier of the user.
     * @return \Illuminate\Http\Response,   The view of the ranking.
     */
    public function index($userId = null)
    {
        // Define the id of the user:
        $userId = isset($_POST['id']) ? $_POST['id'] : (isset($_GET['id']) ? $_GET['id'] : (!is_null($userId) ? $userId : false));
        // Define the specific user:
        $user = User::where('id', $userId)->first();
        // Check if the user is found:
        if($user) {
            // Define the facade for the ranking:
            $rankingFacade = new RankingFacade();
            // Get the ranking for the user:
            $ranking = $rankingFacade->user($user->id);
            // Check if the ranking is found:
            if(is_array($ranking)){
                // Render the view:
                return view('user.ranking', compact(array('ranking', 'user')));
            } else {
                // Display an error:
//                $validator->getMessageBag()->add('user', 'Er is geen gebruiker gevonden met het ID: "' . $userId . '"');
            }
        } else {
            // Display an error:
//                $validator->getMessageBag()->add('user', 'Er is geen gebruiker gevonden met het ID: "' . $userId . '"');
        }
        // Return to the home:
        return redirect('/');
    }
}
