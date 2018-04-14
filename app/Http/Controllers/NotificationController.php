<?php


namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{

    public function index()
    {
        return view('notifications');
    }

    public function post()
    {
        $userInfo = Auth::user();

        // update all unread notifications to read
        Notification::where("user_id", $userInfo->id)->where("read", 0)->update(["read" => 1]);

        return redirect(route("notifications"));
    }

}