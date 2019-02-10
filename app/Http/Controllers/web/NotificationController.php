<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NotificationController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Method to get list of notification
     * @return view
     */
    public function getNotificationList()
    {
        $userId = Auth::user()->id;
        $notificationList = Notification::where('receiver_id', '=', $userId)->orderBy('id', 'DESC')->paginate(10);
        Notification::where('receiver_id', '=', $userId)->update(['seen' => 1]);

        return View('web.notification')->with('notificationList', $notificationList);
    }

    /**
     * Method to delete a notification
     * @return view
     */
    public function deleteNotification($id)
    {
        Notification::findOrFail($id)->delete();
        return redirect('notification-lists');
    }

    /**
     * Method to mark notifications as read
     * @return view
     */
    public function seenNotification($id)
    {
        Notification::where('id', $id)->update(['seen' => 1]);
        return View('web.user-dashboard');
    }
}
