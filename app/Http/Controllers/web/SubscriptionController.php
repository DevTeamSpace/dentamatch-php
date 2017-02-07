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
    
    public function getConnectLink(){
        $auth_uri = "https://connect.stripe.com/oauth/authorize";
        $redirect = url('stripe/connect');

        $client_id = env('STRIPE_CLIENT_ID');

        $authorize_request_body = array(
          'response_type' => 'code',
          'scope' => 'read_write',
          'client_id' => $client_id,
          'redirect_uri' => $redirect,
          'stripe_user[email]' => Auth::user()->email
        );

        $url = $auth_uri . '?' . http_build_query($authorize_request_body);

        echo "<script>location.href =' $url';</script>";
    } //End of function getConnectLink
    
    public function getStripeConnect(){
//        $stripeToken = $_GET['code'];
            $client_id = env('STRIPE_CLIENT_ID');
            $client_secret = env('STRIPE_SECRET_KEY');
            $token_uri = env('STRIPE_TOKEN_URI');

            $code = $_GET['code'];
            $token_request_body = array(
              'grant_type' => 'authorization_code',
              'client_id' => $client_id,
              'code' => $code,
              'client_secret' => $client_secret
            );

            $req = curl_init($token_uri);
            curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($req, CURLOPT_POST, true );
            curl_setopt($req, CURLOPT_POSTFIELDS, http_build_query($token_request_body));

            // TODO: Additional error handling
            $respCode = curl_getinfo($req, CURLINFO_HTTP_CODE);

            $stripe = json_decode(curl_exec($req), true);

            curl_close($req);

            if(isset($stripe['stripe_user_id'])){
                $updateToken = RecruiterProfile::updateStripeToken($stripeToken);
                dd($updateToken);
            }else{
                dd('no');
            }
//        $createCustomer = \Stripe\Customer::create(array(
//            "description" => "Customer for ".Auth::user()->email,
//            "source" => $stripeToken, // obtained with Stripe.js
//            "email" => Auth::user()->email
//        ));
//        dd($createCustomer);
    }
}
