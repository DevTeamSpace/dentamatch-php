<?php

namespace App\Http\Controllers\web;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
