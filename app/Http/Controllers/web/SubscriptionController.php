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
        if(isset($_REQUEST['code'])){
            $client = new \GuzzleHttp\Client();
            $authCredentials = $client->post('https://connect.stripe.com/oauth/token', [
                "form_params" => [
                    "client_secret" => env('STRIPE_SECRET_KEY'),
                    "code" => $_REQUEST['code'],
                    "grant_type" => "authorization_code",
                    "client_id" => env('STRIPE_CLIENT_ID')
                ]
            ]);
            $result = json_decode($authCredentials->getBody()->getContents());
            if(isset($result->stripe_user_id)){
                RecruiterProfile::updateStripeToken($result->stripe_user_id);
                $customerId = RecruiterProfile::where('user_id', Auth::user()->id)->first();
                $createCustomer = \Stripe\Customer::create(array(
                    "description" => "Customer for".Auth::user()->email,
                    "email" => Auth::user()->email,
                    "source" => $result->stripe_user_id
                ));
            }
        }else{
            $response = redirect('stripe/errors');
        }
        return $response;
    }
}
