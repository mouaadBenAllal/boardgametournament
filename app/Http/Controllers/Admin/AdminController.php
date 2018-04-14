<?php

namespace App\Http\Controllers\Admin;

use App\Handlers\UserHandler;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\UrlGenerator;

class AdminController extends Controller {
    /**
     * Function that gets initialized each time this controller is constructed.
     */
    public function index() {
        /**
         * Define the category names and the
         * routes that are defined in the web.app
         */
        $panel = [
            ['name' => 'Categorie', 'route' => 'admin/category'],
            ['name' => 'Rollen', 'route' => 'admin/role'],
            ['name' => 'Gebruikers', 'route' => 'adminUsers'],
            ['name' => 'Bordspellen', 'route' => 'admin/boardgame'],
            ['name' => 'Toernooien', 'route' => 'admin/tournament'],
        ];
        // Return the routes to the view:
        return view('admin.index', compact('panel'));
    }
}