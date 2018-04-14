<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserHasAchievement extends Model
{
    /**
     * The variable to store the name of the table.
     * @var string
     */
    protected $table = 'user_has_achievement';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'achievement_id'
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
     * Get the user record associated with the userhasachievement.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Get the achievement record associated with the userhasachievement.
     */
    public function achievement()
    {
        return $this->belongsTo('App\Models\Achievement');
    }
}
