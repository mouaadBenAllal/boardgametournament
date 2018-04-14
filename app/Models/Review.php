<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    /** Constant for the review to be positive */
    const STATE_POSITIVE = 1;

    /** Constant for the review to be negative */
    const STATE_NEGATIVE = 0;

    /**
     * The variable to store the name of the table.
     * @var string
     */
    protected $table = 'reviews';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'state', 'user_id', 'boardgame_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * Get the user record associated with the review.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Get the boardgame record associated with the review.
     */
    public function boardgame()
    {
        return $this->belongsTo('App\Models\Boardgame');
    }

    public $timestamps = false;
}
