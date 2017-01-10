<?php

namespace App\Http\Controllers\web;

use Config;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    protected $user;
    
    public function __construct() {
        $this->middleware('auth');
        $this->user = Auth::user();
    }
    
    public function login(Request $request)
    {
        
    }
}
