<?php

namespace App\Http\Controllers\cms;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\AppMessage;
use Yajra\Datatables\Datatables;
use Session;
use Log;
class AppMessageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('cms');
    }

    /**
     * Show the form to create a new appMessage.
     *
     * @return Response 
     */
    public function create()
    {
        return view('cms.appMessage.create');
    }
    
    /**
     * List all app-messages.
     *
     * @param  array  $data
     * @return User
     */
    protected function index()
    {  
        return view('cms.appMessage.index');
    }
    
    /**
     * Show the form to update an existing appMessage.
     *
     * @return Response
     */
    public function edit($id)
    {
        $appMessage = AppMessage::findById($id);
        
        return view('cms.appMessage.update',['appMessage'=>$appMessage]);
    }

    /**
     * Store a new/update appMessage.
     *
     * @param  Request  $request
     * @return return to lisitng page
     */
    public function store(Request $request)
    {
        try{
            $reqData = $request->all();
            $rules = array(
                'message' => array('required','min:2','max:150'),
            );

            $validator = Validator::make($reqData, $rules);
                if ($validator->fails()) {
                    return redirect()->back()
                                ->withErrors($validator)
                                ->withInput();
                }
            if(isset($request->id)){
                $appMessage = AppMessage::find($request->id); 
                $appMessage->messageSent = isset($request->message_sent) ? 1 : 0;
                $msg = trans('messages.app_message_updated');
            }
            else{
                $appMessage = new AppMessage;
                $msg = trans('messages.app_message_added');
            }

            $appMessage->message = $request->message;
            $appMessage->messageTo = $request->message_to;
            $appMessage->save();
            
            Session::flash('message',$msg);
            return redirect('cms/notify/index');
        } catch (\Exception $e) {
            Log::error($e);
        }
    }
    
    /**
     * Send notification to users.
     *
     * @param  AppMessage  $id
     * @return return message
     */
    public function sendNotification($id){
        $appMessage = AppMessage::findById($id);
        
        if(!$appMessage->messageSent){
            $appMessage->messageSent=1;
            $appMessage->cronMessageSent=0;
            $appMessage->save();   
       }else {
            Session::flash('message',trans('messages.already_sent_message'));
        }
        
        return redirect('cms/notify/index');
    }
    
    /**
     * Soft delete a appMessage.
     *
     * @param  AppMessage  $id
     * @return return to lisitng page
     */
    public function delete($id){
        AppMessage::findOrFail($id)->delete();
        Session::flash('message',trans('messages.app_message_deleted'));
    }

    public function messageList(){
        try{
        $appMessages = AppMessage::SELECT(['message','message_to','message_sent','created_at','id'])->orderBy('created_at', 'desc')->get();
        return Datatables::of($appMessages)
                ->removeColumn('id')
                ->addColumn('messageTo', function ($appMessages) {
                    $textMessage = ['1'=>'All','2'=>'Recruiter','3'=>'Jobseeker'];
                    return $textMessage[$appMessages->message_to];
                })
                ->addColumn('messageSent', function ($appMessages) {
                    $sentLink = url('cms/notify/'.$appMessages->id.'/send');
                    $link = '<a href="'.$sentLink.'"  class="btn btn-xs btn-primary"><i class="fa fa-send"></i> Send Notification</a>&nbsp;';
                    return ($appMessages->messageSent)?'Notification Sent':$link;})
                ->addColumn('createdAt', function ($appMessages) {
                    return $appMessages->created_at;})
                ->addColumn('action', function ($appMessages) {
                    $edit = url('cms/notify/'.$appMessages->id.'/edit');
                    $delete =url('cms/notify/'.$appMessages->id.'/delete');
                    $action = '<a href="'.$edit.'"  class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>&nbsp;';
                    $action .= '<a href="'.$delete.'" onclick="deleteRecord(this);return false;"  class="delete btn btn-xs btn-primary"><i class="fa fa-remove"></i> Delete</a>';
                    return $action;
                       
                })
                ->make(true);
        }catch (\Exception $e) {
            Log::error($e);
        }              
    }
}
