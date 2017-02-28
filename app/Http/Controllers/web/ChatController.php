<?php

namespace App\Http\Controllers\web;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;
use App\Models\ChatUserLists;

class ChatController extends Controller
{
    protected $viewData;
    public function __construct() {
        $this->middleware('auth');
    }
    
    public function returnView($viewFileName){
        return view('web.chat.'.$viewFileName,$this->viewData);
    }
    
    public function getChatSeekerList(Request $request){
        try{
            $this->viewData['seekerList'] = ChatUserLists::getSeekerListForChat(Auth::id());
            
            return $this->returnView('view');
        } catch (\Exception $e) {
            Log::error($e);
            return view('web.error.',["message" => $e->getMessage()]);
        }
    }
}
