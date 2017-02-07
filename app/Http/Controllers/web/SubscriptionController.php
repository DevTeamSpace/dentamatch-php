<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\RecruiterProfile;
use App\Models\RecruiterOffice;

class SubscriptionController extends Controller {
    
    public function __construct(){
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
    }

    public function getSubscription(){
        return view('web.subscription');
    }
    
    public function getSubscriptionList(){
        $recruiterOffice = RecruiterOffice::getAllOffices();
        $subscription = [];
        foreach ($recruiterOffice as $office){
            if($office['free_trial_period'] != 0){
                $subscription['data'] = $office;
                break;
            }
        }
        return $subscription;
    }
    
    public function getStripeConnect(){
        $stripeToken = $_GET['code'];
        $updateToken = RecruiterProfile::updateStripeToken($stripeToken);
        $createCustomer = \Stripe\Customer::create(array(
            "description" => "Customer for ".Auth::user()->email,
            "source" => $stripeToken, // obtained with Stripe.js
            "email" => Auth::user()->email
        ));
        dd($createCustomer);
    }
}
