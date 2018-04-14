<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Session extends Model
{
    /**
     * The variable to store the name of the table.
     * @var string
     */
    protected $table = 'sessions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'round', 'completed', 'tournament_id'
    ];

    /**
     * The variable to store timestamp value.
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * Get the tournament record associated with the session.
     */
    public function tournament()
    {
        return $this->belongsTo('App\Models\Tournament');
    }

    /**
     * Get the sessionhasuser for the session.
     */
    public function sessionHasUser()
    {
        return $this->hasMany('App\Models\SessionHasUser');
    }



    public static function getLastRoundNumber($tournamentId)
    {
        $highestRound = DB::table('sessions')
            ->select('round')
            ->where('tournament_id', '=', $tournamentId)
            ->orderBy('round', 'desc')
            ->first();

        if ($highestRound != null) {
            return $highestRound->round;
        } else {
            return 0;
        }
    }

    public static function getSessionSize($tournamentId)
    {
        return Tournament::find($tournamentId)->session_size;
    }


    /**
     * save the sessions to the database
     *
     * @param $listOfSessions
     * @param $tournamentId
     */
    public static function saveSessionToDatabase($listOfSessions, $tournamentId)
    {
        //we need these later to see if we need to give automatic wins
        $boardGameId = Boardgame::getBoardGameIdFromTournament($tournamentId);
        $minSessionSize = Boardgame::getMinSessionSizeForBoardGame($boardGameId);

        //here we set the new round number from the tournament
        $round = Session::getLastRoundNumber($tournamentId) + 1;

        //loop through all sessions in the list
        foreach ($listOfSessions as $session) {
            // set if the users win if the the session has fewer players then the minimum requirement
            $result = count($session) < $minSessionSize ? 1 : 0;
            // insert the session into the database and store the id that the session has in de database
            $id = DB::table('sessions')->insertGetId(
                ['round' => $round, 'completed' => 0, 'tournament_id' => $tournamentId]
            );

            //insert the players into the session has user table so that they are connected to the session
            foreach ($session as $user) {
                DB::table('session_has_user')->insert(
                    ['result' => $result, 'session_id' => $id, 'user_id' => $user->user_id]
                );
            }

        }
    }
}
