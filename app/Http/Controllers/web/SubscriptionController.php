<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class SubscriptionController extends Controller {

    public function getSubscription(){
        return view('web.subscription');
    }
}
