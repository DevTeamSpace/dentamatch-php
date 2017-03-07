<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\RecruiterProfile;
use App\Models\RecruiterOffice;
use App\Http\Requests\CreateSubscriptionRequest;
use App\Http\Requests\AddCardRequest;
use App\Http\Requests\DeleteCardRequest;
use App\Http\Requests\UnsubscribeRequest;
use App\Http\Requests\EditCardRequest;
use App\Http\Requests\ChangeSubscriptionPlanRequest;
use App\Http\Requests\SubscribeAgainRequest;
use App\Models\Notification;
use Log;
use DB;
use Illuminate\Pagination\Paginator;

class NotificationController extends Controller {
    private $response = [];
    
    public function __construct(){
        $this->middleware('auth');
       
    }

    
    public function getNotificationList(){
        $userId = Auth::user()->id;
        $notificationList = Notification::where('receiver_id', '=', $userId)->orderBy('id', 'DESC')->paginate(5);
        Notification::where('receiver_id', '=', $userId)->update(['seen'=>1]);
        
        return View('web.notification')->with('notificationList' , $notificationList);
   
    }
    
    public function deleteNotification($id){
        Notification::findOrFail($id)->delete();
        Session::flash('message',trans('messages.location_deleted'));
    }
    
    
}