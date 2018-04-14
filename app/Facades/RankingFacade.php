<?php

namespace App\Facades;

use App\Models\Boardgame;
use App\Models\SessionHasUser;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;

/**
 * Class to handle everything for display of the ranking.
 */
class RankingFacade
{
    /**
     * Function to get the ranking of a specific boardgame.
     * @param $boardgameId,                 The identifier of the boardgame.
     * @return mixed,                       The ranking object, false otherwise.
     */
    public function boardgame($boardgameId = null)
    {
        // Define the id of the boardgame:
        $boardgameId = isset($_POST['id']) ? $_POST['id'] : (isset($_GET['id']) ? $_GET['id'] : (!is_null($boardgameId) ? $boardgameId : false));
        // Define the specific boardgame:
        $boardgame = Boardgame::where('id', $boardgameId)->first();
        // Define an array for the result:
        $result = array();
        // Define an array for the ranking:
        $ranking = array();
        // Check if the boardgame exists:
        if($boardgame) {
            // Define the users:
            $users = User::all();
            // Loop trough the users:
            foreach ($users as $user) {
                // Add the user to the array:
                $ranking[$user->id] = array(
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'win' => 0
                );
            }
            // Define all the tournaments from the boardgame:
            $tournaments = Tournament::where('boardgame_id', $boardgame->id)->get();
            // Loop trough all the tournaments:
            foreach ($tournaments as $tournament) {
                // Get all the sessions of the tournament:
                $sessions = $tournament->sessions()->get();
                // Loop trough all the sessions:
                foreach ($sessions as $session) {
                    // Get all the sessionhasuser for the sessions:
                    $sessionHasUser = $session->sessionHasUser()->get();
                    // Loop trough the sessionhasuser objects:
                    foreach ($sessionHasUser as $object) {
                        // Check if the result is a win:
                        if ($object->result == SessionHasUser::RESULT_WIN) {
                            // Check if the key 'win' exists in the array:
                            if (key_exists('win', $ranking[$object->user_id])) {
                                // Add one to the result from user in the array:
                                $ranking[$object->user_id]['win'] += 1;
                            } else {
                                // Define the user in the array as 1 win:
                                $ranking[$object->user_id]['win'] = 1;
                            }
                        }
                    }
                }
            }
        } else {
            // Return failure:
            return false;
        }
        // Re-define the result:
        $ranking = collect($ranking);
        // Sort the result:
        $sortRanking = $ranking->sortByDesc('win');
        // Loop trough the sorted array:
        foreach($sortRanking->values()->all() as $key => $object){
            // Add the object to the ranking:
            $result[$key + 1] = $object;
        }
        // Define the final result:
        $finalResult = array();
        // Filter the result:
        foreach($result as $array){
            // Check if the value from the array has more then 0 win:
            if($array['win'] != 0) {
                // Add to the final result:
                $finalResult[] = $array;
            }
        }
        // Return the result:
        return $finalResult;
    }

    /**
     * Function to get the ranking of a specific user.
     * @param $userId,                 The identifier of the user.
     * @param $amount,                 The amount of top boardgames for the user you want to receive.
     * @return mixed,                  The ranking object, false otherwise.
     */
    public function user($userId = null, $amount = null)
    {
        // Define the id of the boardgame:
        $userId = isset($_POST['id']) ? $_POST['id'] : (isset($_GET['id']) ? $_GET['id'] : (!is_null($userId) ? $userId : false));
        // Define the specific user:
        $user = User::where('id', $userId)->first();
        // Define an array for the result:
        $result = array();
        // Define an array for the ranking:
        $ranking = array();
        // Check if the boardgame exists:
        if($user) {
            // Define the boardgames:
            $boardgames = Boardgame::all();
            // Loop trough the boardgames:
            foreach ($boardgames as $boardgame) {
                // Add the boardgame to the array:
                $ranking[$boardgame->id] = array(
                    'user_id' => $user->id,
                    'boardgame_id' => $boardgame->id,
                    'boardgame_name' => $boardgame->name,
                    'win' => 0
                );
                // Define all the tournaments from the boardgame:
                $tournaments = Tournament::where('boardgame_id', $boardgame->id)->get();
                // Loop trough all the tournaments:
                foreach ($tournaments as $tournament) {
                    // Get all the sessions of the tournament:
                    $sessions = $tournament->sessions()->get();
                    // Loop trough all the sessions:
                    foreach ($sessions as $session) {
                        // Get all the sessionhasuser for the sessions:
                        $sessionHasUser = $session->sessionHasUser()->get();
                        // Loop trough the sessionhasuser objects:
                        foreach ($sessionHasUser as $object) {
                            // Check if the result is a win and is for the user:
                            if ($object->user_id == $user->id && $object->result == SessionHasUser::RESULT_WIN) {
                                // Check if the key 'win' exists in the array:
                                if (key_exists('win', $ranking[$boardgame->id])) {
                                    // Add one to the result from user in the array:
                                    $ranking[$boardgame->id]['win'] += 1;
                                } else {
                                    // Define the user in the array as 1 win:
                                    $ranking[$boardgame->id]['win'] = 1;
                                }
                            }
                        }
                    }
                }
            }

        } else {
            // Return failure:
            return false;
        }
        // Re-define the result:
        $ranking = collect($ranking);
        // Sort the result:
        $sortRanking = $ranking->sortByDesc('win');
        // Loop trough the sorted array:
        foreach($sortRanking->values()->all() as $key => $object){
            // Check if the amount is not null:
            if(!is_null($amount)) {
                // Check if the key is already bigger then the amount:
                if($amount >= ($key + 1)){
                    // Add the object to the ranking:
                    $result[$key + 1] = $object;
                }
            } else {
                // Add the object to the ranking:
                $result[$key + 1] = $object;
            }
        }
        // Define the final result:
        $finalResult = array();
        // Filter the result:
        foreach($result as $array){
            // Check if the value from the array has more then 0 win:
            if($array['win'] != 0) {
                // Add to the final result:
                $finalResult[] = $array;
            }
        }
        // Return the result:
        return $finalResult;
    }
}
