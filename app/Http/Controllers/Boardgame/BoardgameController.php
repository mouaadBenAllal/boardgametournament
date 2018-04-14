<?php

namespace App\Http\Controllers\Boardgame;

use App\Components\FlashSession;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\Boardgame;
use Illuminate\Http\Response;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use function Sodium\compare;

class BoardgameController extends Controller
{

    /** Constant to display the amount in the pagination */
    const PAGINATION_AMOUNT = 12;
    const TAKE_AMOUNT = 3;

    /**
     * Display random boardgames on the homepage with an specified limit
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Welkom op BGT4 Tournament";
        //Check if user is signed in
        // Define the suggestion array:
        $suggestions = array();
        if(Auth::check()) {
            // Retrieve random boardgames with limit:
            $boardgames = Boardgame::inRandomOrder()->take(6)->get();
            //Find id of User
            $userId = Auth::id();
            //Find random 'liked' boardgame->id
            $review = Review::inRandomOrder()->where('user_id', '=', $userId)->where('state', '=', 1)->first();
            //Check if user has reviewed a game
            if($review) {
                //find category of 'liked' boardgame, save it in variable
                $category = Boardgame::find($review->boardgame_id)->category()->first();
                // Boardgames:
                $boardgamesSuggestions = Boardgame::where('category_id', $category->id)->get();
                foreach($boardgamesSuggestions as $boardgamesSuggestion) {
                    if(count(Review::where('user_id', Auth::id())->where('boardgame_id', $boardgamesSuggestion->id)->first()) == 0){
                        if(count($suggestions) < self::TAKE_AMOUNT)  {
                            $suggestions[] = $boardgamesSuggestion;
                        }
                    }
                }
                // Return the boardgames, review and suggestions to the view:
                return view('layouts.home', [
                    'boardgames' => $boardgames,
                    'review' => $review,
                    'suggestions' => $suggestions
                ]);
            }
            //// Return the boardgames and review to the view:
            return view('layouts.home', [
                'boardgames' => $boardgames,
                'review' => $review,
                'suggestions' => $suggestions
            ]);
        } else{
            $boardgames = Boardgame::inRandomOrder()->take(self::TAKE_AMOUNT)->get();
            // Return the boardgames to the view:
            return view('layouts.home', compact('boardgames', 'title', 'suggestions'));
        }
    }


    /**
     * Retrieve and return all the existing boardgames to the view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAllBoardgames(Request $request)
    {
        // Retrieve all boardgames:
        $boardgames = Boardgame::paginate(self::PAGINATION_AMOUNT);
        $title = "Bordspellen";
        if($request->exists('search')){
            if($this->search($request->input('search')) == false){
                FlashSession::addAlert('error', 'Er bestaat geen boardgame: "' . $request->input('search') . '"');
            } else {
                $boardgames = $this->search($request->input('search'));
            }
        }
        // Define the categories:
        $categories = Category::all();
        // Return the boardgames to the view:
        return view('boardgame.index', compact('boardgames', 'categories', 'title'));
    }

    /**
     * Retrieve a single boardgame based on given $id
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function get($id)
    {
        // Retrieve the boardgame:
        $boardgame = Boardgame::all()->where('id', '=', $id)->first();

        //Count the Likes of boardgame
        $likes = Review::all()->where('boardgame_id', '=', $id)->where('state', '=', 1)->count();

        //Count the Dislikes for the boardgame
        $dislikes = Review::all()->where('boardgame_id', '=', $id)->where('state', '=', 0)->count();

        // Retrieve the categories:
        $categories = Category::all();

        // Return to view with boardgames data:
        return view('boardgame.get', [
            'boardgame' => $boardgame,
            'likes' => $likes,
            'dislikes' => $dislikes,
            'categories' => $categories
        ]);
    }

    /**
     * Retrieve all the records with the same name
     *
     * @param   Request $request
     * @return  \Illuminate\Http\Response
     */
    public function search($search)
    {
        // Retrieve the boardgame object:
        $boardgames = Boardgame::where('name', 'like', '%' . $search . '%')->get();
        // Check if there are no boardgames found:
        if(!(bool)count($boardgames)){
            // Flash an error:
            return false;
        }
        // Return searched boardgame to the view:
        return $boardgames;
    }

    public function updateLike(Request $request, $id)
    {
        $userId = Auth::id();
        $review = Review::all()->where('boardgame_id', '=', $id)->where('user_id', '=', $userId)->first();

        //check if user is signed in and review already exist, if not create new review
        if(!$review && Auth::check()){
            $newReview = new Review(array(
                'state' => $request->get('like'),
                'user_id' => $userId,
                'boardgame_id' => $id,
            ));
            $newReview->save();
        }

        //check if user is signed in and if user has review, if true change the state
        if($review && Auth::check()) {
                $review->state = $request->get('like');
                $review->user_id = $userId;
                $review->boardgame_id = $id;
                $review->save();
        }

        //redirect to same boardgame page
        return redirect('/boardgame/get/'.$id);

        }

}
