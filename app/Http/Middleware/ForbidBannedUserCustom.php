<?php

namespace App\Http\Middleware;

use App\Components\FlashSession;
use Closure;
use Illuminate\Contracts\Auth\Guard;

class ForbidBannedUserCustom
{
    /**
     * The Guard implementation.
     *
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * @param \Illuminate\Contracts\Auth\Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $this->auth->user();
        if ($user && $user->isBanned()) {
            \Session::flush();
            FlashSession::addAlert('error', 'Dit account is verbannen, contacteer de administrator van de website.');
            return redirect('login');
        }
        return $next($request);
    }
}
