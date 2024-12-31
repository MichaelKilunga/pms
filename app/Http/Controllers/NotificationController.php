<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use  Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        // $notifications = Notification::where('notifiable_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        $unreadNotifications = Notification::where('notifiable_id', Auth::user()->id)->where('read_at', null)->count();
        // dd($unreadNotifications);
        return view('notifications.index', compact( 'unreadNotifications'));
    }

    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->read_at = now();
        $notification->save();

        return redirect()->back()->with('success_disabled', 'Notification marked as read!');
    }

    public function readAll()
    {
        $notifications = Notification::where('notifiable_id', Auth::user()->id)->get();
        foreach ($notifications as $notification) {
            // $notification = Notification::findOrFail($id);
            if (!$notification->read_at) {
                $notification->read_at = now();
                $notification->save();
            }
        }
        return redirect()->back()->with('success', 'All notifications are marked as read!');
    }
}
