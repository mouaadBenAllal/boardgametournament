<?php

namespace App\Http\Controllers;

use App\Components\FlashSession;
use App\Facades\RankingFacade;
use App\Models\Achievement;
use App\Models\Review;
use App\Models\UserHasAchievement;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class for handling everything for the user within the platform.
 */
class UserController extends Controller
{

    /**
     * Function to edit an existing user.
     * @param $userId,                      The identifier of a user.
    */
    public function edit(){
        // Define the id of the boardgame:
        $userId = Auth::user()->id;
        // Define the user:
        $countries = ["Nederland", "België", "Duitsland"];
        $user = User::where('id', $userId)->get()->first();
        // Check if the user is found:
        if(!$user){
            // Display an error:
            FlashSession::addAlert('error','Er is geen gebruiker gevonden met dit ID');
            // Return to the overview:
            return redirect('/');
        }
        // Return the view:
        return view('user.edit', compact('user', 'countries'));
    }

    /**
     * Function to display the data of a user.
     * @param $userId,                      The identifier of a user.
     */
    public function get($username){
        // Define the user:
        $user = User::where('username', $username)->first();
        $userHasAchievements = UserHasAchievement::all()->where('user_id', $user->id);
        $achievementList = [];
        foreach($userHasAchievements as $userHasAchievement){
            array_push($achievementList, Achievement::where('id', $userHasAchievement->achievement_id)->get()->first()->name);
        }
        // Check if any user is found:
        if(!$user){
            // Display an error:
            FlashSession::addAlert('error','Er is geen gebruiker gevonden met dit ID');
            // Return to the overview:
            return redirect('/');
        }
        // Define the favorites of the user:
        $favorites = $user->reviews()->where('state', Review::STATE_POSITIVE)->get();
        // Define the ranking facade:
        $rankingFacade = new RankingFacade();
        // Define the ranking of the user:
        $ranking = $rankingFacade->user($user->id, 10);
        // Return the view:
        return view('user.get', compact('user', 'favorites', 'ranking', 'achievementList'));
    }

    /**
     * Update the users information
     *
     * @param Request $request
     * @return void
     */
    public function update(Request $request) {
        $user = User::where('id', Auth::id())->first();
        $this->validate($request, [
            'first_name' => 'nullable',
            'prefix' => 'nullable',
            'last_name' => 'nullable',
            'birthday' => 'nullable|date',
            'gender' => 'nullable',
            'description' => 'nullable',
            'city' => 'nullable',
            'country' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);
        // Check if the request has a file:
        if($request->hasFile('image')){
            // Define the image:
            $user->image = 'data:' . $request->file('image')->getMimeType() . ';base64,' . base64_encode(file_get_contents($request->file('image')->path()));
        }
        // Define the parameters:
        $user->first_name = $request->first_name;
        $user->prefix = $request->prefix;
        $user->last_name = $request->last_name;
        $user->date_of_birth = $request->birthday;
        $user->gender = $request->gender;
        $user->description = $request->description;
        $user->city = $request->city;
        $user->country = $request->country;
        // Try to save the user:
        if(!$user->save()){
            // Display an error:
            FlashSession::addAlert('error','Het aanpassen van de gebruiker is mislukt');
            // Return to the overview:
            return redirect('/user');
        } else {
            // Display an success:
            FlashSession::addAlert('success','Het aanpassen van de gebruiker is gelukt');
        }
        // Return to the overview:
        return redirect('/user/get/' . $user->username);
    }
}
