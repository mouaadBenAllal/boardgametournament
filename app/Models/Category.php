<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    /**
     * The variable to store the name of the table.
     * @var string
     */
    protected $table = 'categories';

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
        'name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * Get the boardgames for the category.
     */
    public function boardgames()
    {
        return $this->hasMany('App\Models\Boardgame');
    }
}
