<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tournament extends Model
{
    /**
     * Define the usage for softDeletes
     */
    use SoftDeletes;

    /**
     * The variable to store the name of the table.
     * @var string
     */
    protected $table = 'tournaments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'session_size', 'private', 'completed', 'deleted_at', 'boardgame_id', 'user_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * Get the boardgame record associated with the tournament.
     */
    public function boardgame()
    {
        return $this->belongsTo('App\Models\Boardgame');
    }

    /**
     * Get the user record associated with the tournament.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Get the sessions for the tournament.
     */
    public function sessions()
    {
        return $this->hasMany('App\Models\Session');
    }

    /**
     * Get the invites for the tournament.
     */
    public function invites()
    {
        return $this->hasMany('App\Models\Invite');
    }

    /**
     * Get the tournamenthasuser for the tournament.
     */
    public function tournamentHasUser()
    {
        return $this->hasMany('App\Models\TournamentHasUser');
    }
}
