<?php

namespace App\Http\Controllers\Auth;

use App\Handlers\UserHandler;
use App\Models\Achievement;
use App\Models\Role;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\UserHasAchievement;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Components\FlashSession;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required|string|max:255|alpha_dash|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // Define an random number:
        $confirmation_code = str_random(15);

        // Find the role:
        $roleId = Role::where('authority', '=', Role::DEFAULT_ROLE)->get()->first()->id;
        // Check if any role is found:
        if(!$roleId){
            // Define an error flashSession:
            FlashSession::addAlert('error', 'Er is geen rol gevonden');
            // Redirect to the register page:
            return redirect('/register');
        }
        // Assigning user credentials:
        $user = User::create([
            'email' => $data['email'],
            'username' => $data['username'],
            'image' => (string)file_get_contents('./../defaultimg.txt'),
            'password' => bcrypt($data['password']),
            'confirmation_code' => $confirmation_code,
            'role_id' => $roleId
        ]);

        // Define the email content:
        $email = $data['email'];
        $username = $data['username'];
        $content = [
            'email' => $data['email'],
            'username' => $data['username'],
            'confirmation_code' => $confirmation_code
        ];

        // Sending the confirmation code through email:
        Mail::send('auth.verify', $content, function ($message) use ($email, $username) {
            // Define the email body:
            $message->to($email, $username)->subject('Bevestig uw e-mailadres voor BGT');
        });

        // Check if mail is send:
        if (count(Mail::failures()) > 0) {
            // Define an error flashSession:
            FlashSession::addAlert('error', 'Mail kan niet verstuurd worden, probeer later opnieuw');
        } else {
            FlashSession::addAlert('success', 'Er is een bevestigingsmail verstuurd naar: ' . $email);
        }

        // Redirect to home:
        return $user;
    }

    /**
     * Confirm account for registered user.
     *
     * @param $confirmationCode
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function confirm($confirmationCode) {
        // Checks if confirmation code exists:
        if (!$confirmationCode) {
            // Define error flashmessage:
            FlashSession::addAlert('error', 'Verificatie code is niet geldig.');
        }
        // Define the updated user:
        $user = User::where('confirmation_code', $confirmationCode)->first();
        // Updating the user data:
        $user->confirmed = 1;
        $user->confirmation_code = null;
        $achievement = new UserHasAchievement();
        $achievement->user_id = $user->id;
        $achievement->achievement_id = Achievement::VERIFICATION_ACHIEVEMENT_ID;
        $achievement->save();
        // Save the updated user:
        if ($user->save() && $user->confirmed == 1 && $user->confirmation_code == null) {
            // Define flashSession:
            FlashSession::addAlert('success', 'Uw account met email ' . $user->email . ' is bevestigd');
        }
        // Return data to the view:
        return view('auth.login', compact('confirmationCode'));
    }
}
