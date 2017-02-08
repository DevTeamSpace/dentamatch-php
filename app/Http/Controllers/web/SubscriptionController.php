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
        $this->response = [];
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
    
    public function postCreateSubscription(Request $request){
        try{
            $createCustomer = $this->createCustomer();
            if($createCustomer['success'] == true){
                $addCard = $this->addCardForSubscription($request->all(), $createCustomer['data']['id']);
                $createSubscription = $this->addUserTOSubscription($createCustomer['data']['id'], $request->subscriptionType, $request->trailPeriod);
                $this->response['success'] = true;
                $this->response['message'] = trans('messages.user_subscribed');
            }else{
                $this->response['success'] = false;
                $this->response['message'] = trans('messages.cannot_subscribe');
            }
        } catch (\Exception $e) {
            $this->response['message'] = $e->getMessage();
        }
        return $this->response;
    }
    
    public function addUserTOSubscription($customerId, $subscriptionType, $trailPeriod){
        try{
            $now = \Carbon\Carbon::now();
            $fotDiff = \Carbon\Carbon::now();
            $addMonths = $now->addMonths($trailPeriod);
            $trailPeriodDays = $addMonths->diff($fotDiff)->days;
            if($subscriptionType == 1){
                $planId = "six-months";
            }else{
                $planId = "one-year";
            }
            \Stripe\Subscription::create(array(
                "customer" => $customerId,
                "plan" => $planId,
                "trial_period_days" => $trailPeriodDays
            ));
            $this->response['success'] = true;
            $this->response['message'] = trans('messages.user_added_to_subscription');
        } catch (\Exception $e) {
            $this->response['message'] = $e->getMessage();
        }
        return $this->response;
    }
    
    public function postAddCard(Request $request){
    }
    
    public function addCardForSubscription($cardDetails, $customerId){
        try{
            $expiry = explode('/', $cardDetails['expiry']);
            $month = $expiry[0];
            $year = $expiry[1];
            $cardToken = \Stripe\Token::create(array(
                            "card" => array(
                              "number" => $cardDetails['cardNumber'],
                              "exp_month" => $month,
                              "exp_year" => $year,
                              "cvc" => $cardDetails['cvv']
                            )
                          ));
            $customer = \Stripe\Customer::retrieve($customerId);
            $card = $customer->sources->create(array(
                "source" => $cardToken['id']
            ));
            $this->response['success'] = true;
            $this->response['message'] = trans('messages.card_added');
        } catch (\Exception $e) {
            $this->response = $e->getMessage();
        }
        return $this->response;
    }
    
    public function createCustomer(){
        try{
            $createCustomer = \Stripe\Customer::create(array(
                "description" => "Customer for".Auth::user()->email,
                "email" => Auth::user()->email
            ));
            RecruiterProfile::updateCustomerId($createCustomer['id']);
            $this->response['success'] = true;
            $this->response['data'] = $createCustomer;
            $this->response['message'] = trans('messages.customer_created');
        } catch (\Exception $e) {
            $this->response['success'] = false;
            $this->response['message'] = $e->getMessage();
        }
        return $this->response;
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
            }
        }else{
            $response = redirect('stripe/errors');
        }
        return $response;
    }
}
