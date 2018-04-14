<?php

namespace App\Http\Controllers;

use App\Handlers\UserHandler;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\UrlGenerator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Function that gets initialized each time this controller is constructed.
     */
    public function __construct() {
        // Define the middleware:
        $this->middleware(function ($request, $next) {
            // Define the authenticator:
            $authenticator = $this->urlAuthentication();
            // Handle the authentication:
            if($authenticator != null){
                // Return the authenticator:
                return redirect($authenticator);
            }
            // Return to the request:
            return $next($request);
        });
    }

    /**
     * Function to check if the user has access to the url:
     */
    private function urlAuthentication() {
        // Define the addition of the url:
        $urlAddition = array_key_exists(3, explode('/', url()->current())) ? explode('/', url()->current())[3] : null;
        // Check if the url is for the admin panel:
        if (!is_null($urlAddition) && str_contains('admin', $urlAddition)){
            // Check if the user is logged in:
            if (UserHandler::isLoggedIn()) {
                // Check if the user is an admin:
                if (!UserHandler::isAdmin()) {
                    // Restrict the user from going to this route:
                    return route('/');
                }
            } else {
                // Return to the login form:
                return route('login');
            }
        }
    }
}
