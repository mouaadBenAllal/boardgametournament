<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class TournamentHasUser extends Model
{
    /**
     * The variable to store the name of the table.
     * @var string
     */
    protected $table = 'tournament_has_user';

    /**
     * The variable to store timestamp value.
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tournament_id', 'user_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * Get the tournament record associated with the tournamenthasuser.
     */
    public function tournament()
    {
        return $this->belongsTo('App\Models\Tournament');
    }

    /**
     * Get the user record associated with the tournamenthasuser.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public static function getAllTournamentParticipants($tournamentId)
    {
        $results = DB::table('tournament_has_user')
            ->join('users', 'tournament_has_user.user_id', '=', 'users.id')
            ->select('tournament_has_user.tournament_id', 'tournament_has_user.user_id')
            ->where('tournament_has_user.tournament_id', '=', $tournamentId)
            ->get();

        return $results->toArray();
    }
}
