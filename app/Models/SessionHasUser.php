<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SessionHasUser extends Model
{
    /** Constant for the result win */
    const RESULT_WIN = 1;

    /** Constant for the result loss */
    const RESULT_LOSS = 0;

    /**
     * The variable to store the name of the table.
     * @var string
     */
    protected $table = 'session_has_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'result', 'session_id', 'user_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * The variable to store timestamp value.
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the session record associated with the sessionhasuser.
     */
    public function session()
    {
        return $this->belongsTo('App\Models\Session');
    }

    /**
     * Get the user record associated with the sessionhasuser.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
