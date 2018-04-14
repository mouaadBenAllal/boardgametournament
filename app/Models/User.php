<?php

namespace App\Models;

use Cog\Contracts\Ban\Bannable as HasBansContract;
use Cog\Laravel\Ban\Traits\Bannable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements HasBansContract
{
    use Notifiable;
    use Bannable;

    /**
     * The variable to store the name of the table.
     * @var string
     */
    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'first_name', 'prefix', 'last_name', 'date_of_birth', 'gender', 'description', 'zip_code', 'city', 'country', 'deleted_at', 'role_id', 'password', 'remember_token', 'image', 'banned_at', 'confirmed', 'confirmation_code'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * Get the role record associated with the user.
     */
    public function role()
    {
        return $this->belongsTo('App\Models\Role');
    }

    /**
     * Get the reviews for the user.
     */
    public function reviews()
    {
        return $this->hasMany('App\Models\Review');
    }

    /**
     * Get the tournaments for the user.
     */
    public function tournaments()
    {
        return $this->hasMany('App\Models\Tournament');
    }

    /**
     * Get the tournamenthasuser for the user.
     */
    public function tournamentHasUser()
    {
        return $this->hasMany('App\Models\TournamentHasUser');
    }

    /**
     * Get the sessionhasuser for the user.
     */
    public function sessionHasUser()
    {
        return $this->hasMany('App\Models\SessionHasUser');
    }

    /**
     * Get the invites for the user.
     */
    public function invites()
    {
        return $this->hasMany('App\Models\Invite');
    }

    /**
     * Get the notifications for the user.
     */
    public function notifications()
    {
        return $this->hasMany('App\Models\Notification');
    }

    /**
     * Get the userhasachievement for the user.
     */
    public function userHasAchievement()
    {
        return $this->hasMany('App\Models\UserHasAchievement');
    }

    /**
     * Get the notifications for the user.
     */
    public function userHasNotification()
    {
        return $this->hasMany('App\Models\Notification');
    }
}
