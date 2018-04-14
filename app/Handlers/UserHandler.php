<?php

namespace App\Handlers;

use App\Models\Role;
use Auth;

/**
 * Class to handle everything for handling of the user.
 */
class UserHandler
{
    /**
     * Function to determine if the user is an admin.
     */
    public static function isAdmin(){
        // Get the authentication role of the user:
        $authority = \Illuminate\Support\Facades\Auth::user()->role()->get()->first()->authority;
        // Check if the user is an admin:
        if($authority == Role::AUTHORITY_ADMIN){
            // Return true:
            return true;
        }
        // Return false:
        return false;
    }

    /**
     * Function to determine if the user is logged in.
     */
    public static function isLoggedIn(){
        // Get the authentication role of the user:
        $user = Auth::user();
        // Check if the user is logged in:
        if($user){
            // Return true:
            return true;
        }
        // Return false:
        return false;
    }
}