<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    /** Constant for the user to be an admin */
    const AUTHORITY_ADMIN = 4;
    const DEFAULT_ROLE = 2;
    
    /**
     * The variable to store the name of the table.
     * @var string
     */
    protected $table = 'roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'authority'
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
     * Get the users for the role.
     */
    public function users()
    {
        return $this->hasMany('App\Models\User');
    }
}
