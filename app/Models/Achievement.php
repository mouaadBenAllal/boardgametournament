<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{

    /**
     * Constant for the id of user email verification achievement
     */
    const VERIFICATION_ACHIEVEMENT_ID = 1;

    /**
     * The variable to store the name of the table.
     * @var string
     */
    protected $table = 'achievements';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'amount', 'type_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * Get the type record associated with the achievement.
     */
    public function type()
    {
        return $this->belongsTo('App\Models\Type');
    }

    /**
     * Get the userhasachievements for the achievement.
     */
    public function userHasAchievement()
    {
        return $this->hasMany('App\Models\UserHasAchievement');
    }
}
