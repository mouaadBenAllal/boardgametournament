<?php

namespace App\Http\Controllers\Auth;

use App\Components\FlashSession;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function credentials(Request $request) {
        return array_merge($request->only($this->username(), 'password'), ['confirmed' => 1], ['banned_at' => null]);
    }

    protected function sendFailedLoginResponse(Request $request) {


        FlashSession::addAlert('error', 'Gegevens komt niet overeen, probeer opnieuw');
        // Loads the user:
        $user = User::where($this->username(), $request->{$this->username()})->first();

        // check if the user successfully loaded, and password matches:
        if ($user && Hash::check($request->password, $user->password) && $user->confirmed != 1) {
            // Define not confirmed yet error:
            FlashSession::addAlert('error', 'Dit account is nog niet geactiveerd, bevestig je account voordat je gaat inloggen.');
        }


        // check if the user successfully loaded, and password matches:
        if ($user && Hash::check($request->password, $user->password) && $user->banned_at != null) {
            // Define not confirmed yet error:
            FlashSession::addAlert('error', 'Dit account is verbannen, contacteer de administrator van de website.');
        }

        return redirect()->back()->withInput($request->only($this->username(), 'remember'));
    }
}
