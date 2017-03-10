<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NotificationController extends Controller {
    private $response = [];
    
    public function __construct(){
        $this->middleware('auth');
       
    }

    
    public function getNotificationList(){
        $userId = Auth::user()->id;
        $notificationList = Notification::where('receiver_id', '=', $userId)->orderBy('id', 'DESC')->paginate(10);
        Notification::where('receiver_id', '=', $userId)->update(['seen'=>1]);
        
        return View('web.notification')->with('notificationList' , $notificationList);
   
    }
    
    public function deleteNotification($id){
        Notification::findOrFail($id)->delete();
        return redirect('notification-lists');
    }
    
    
}
