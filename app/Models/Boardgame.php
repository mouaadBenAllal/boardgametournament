<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Boardgame extends Model
{
    use SoftDeletes;

    /**
     * The variable to store the name of the table.
     * @var string
     */
    protected $table = 'boardgames';

    /**
     * The variable to store the dates in.
     * @var array
     */
    protected $dates = ['deleted_at'];

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
        'name', 'description', 'min_players', 'max_players', 'avg_time', 'deleted_at', 'category_id', 'image'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * Get the category record associated with the boardgame.
     */
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    /**
     * Get the reviews for the boardgame.
     */
    public function reviews()
    {
        return $this->hasMany('App\Models\Review');
    }

    /**
     * Get the tournaments for the boardgame.
     */
    public function tournaments()
    {
        return $this->hasMany('App\Models\Tournament');
    }


    public static function getMinSessionSizeForBoardGame($boardgameId)
    {
        $minSize = DB::table('boardgames')
            ->select('min_players')
            ->where('id', '=', $boardgameId)
            ->first();

        return $minSize->min_players;
    }

    //todo: move to model
    public static function getBoardGameIdFromTournament($tournamentId)
    {
        $boardGameId = DB::table('tournaments')
            ->select('boardgame_id')
            ->where('id', '=', $tournamentId)
            ->first();
        return $boardGameId->boardgame_id;
    }

}
