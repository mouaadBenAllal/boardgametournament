<?php

namespace App\Providers;

use App\Components\NotificationCollection;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(NotificationCollection::class, function ($app) {
            $user = Auth::user();

            if (is_null($user)) {
                return new NotificationCollection([]);
            }

            // fetch notifications and combine them in a notification collection
            $notifications = Notification::where("user_id", $user->id)->orderBy('created_at', 'desc')->get()->all();

            // fallback to empty array
            $notifications = is_array($notifications) ? $notifications : [];

            return new NotificationCollection($notifications);
        });
    }
}
