<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\Boardgame;
use Illuminate\Http\Response;

class BoardgameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        /**
         * Retrieve all the boardgames:
         *
         * Using pagination we display maximal 8 boardgames per page
        */
        $boardgames = Boardgame::withTrashed()->paginate(8);

        // Return all the boardgames to the view:
        return view('adminDashboard.boardgames.boardgames', compact('boardgames'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Return to the view:
        return view('adminDashboard.boardgames.createboardgame');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // Assign form data to the database:
        Boardgame::create([
            'name' => $request['name'],
            'description' => $request['description'],
            'min_players' => $request['min_players'],
            'max_players' => $request['max_players'],
            'avg_time' => $request['avg_time'],
            'deleted_at' => $request['deleted_at'],
            'category_id' => $request['category_id']
        ]);

        // Redirect to the overview:
        return redirect('/admin/boardgames');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Define the boardgame:
        $boardgame = Boardgame::withTrashed()->where('id', '=', $id)->first();

        // Return to view with boardgames data:
        return view('adminDashboard.boardgames.boardgames', [
            'boardgames' => [$boardgame]
        ]);
    }

    /**
     * Get the data from the database and returns to the view
     *
     * @param  Boardgame  $boardgames
     * @return \Illuminate\Http\Response
     */
    public function edit(Boardgame $boardgame)
    {
        // Return to the view with boardgames data:
        return view('adminDashboard.boardgame', compact('boardgame'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Boardgame  $boardgames
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Boardgame $boardgame)
    {
        // Find existing boardgame:
        $game = Boardgame::find($boardgame->id);

        // Assign request values to database:
        $game->name = $request['name'];
        $game->description = $request['description'];
        $game->min_players = $request['min_players'];
        $game->max_players = $request['max_players'];
        $game->avg_time = $request['avg_time'];
        $game->created_at = $request['created_at'];
        $game->category_id = $request['category_id'];

        // Save the data to the database:
        $game->save();

        // Redirect to view:
        return redirect('/admin/boardgames');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Boardgame $boardgame)
    {
        //
    }

    /**
     * Delete or restore based on checkbox value
     *
     * @param   $id
     * @return \Illuminate\Http\Response
     */
    public function recover($id)
    {
        // Get boardgame from database:
        $boardgame = Boardgame::withTrashed()->where('id', '=', $id)->first();

        var_dump($boardgame->trashed());

        // Check if boardgames is soft-deleted:
        if ($boardgame->trashed()) {
            // Undo soft-delete:
            $boardgame->restore();
        } else {
            // Soft-delete boardgame:
            $boardgame->delete();
        }

        return view('adminDashboard.boardgames.boardgames');
    }
}
