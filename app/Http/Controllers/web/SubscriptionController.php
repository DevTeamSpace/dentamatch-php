<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\RecruiterProfile;
use App\Models\RecruiterOffice;
use App\Models\SubscriptionPayments;
use App\Http\Requests\CreateSubscriptionRequest;
use App\Http\Requests\AddCardRequest;
use App\Http\Requests\DeleteCardRequest;
use App\Http\Requests\UnsubscribeRequest;
use App\Http\Requests\EditCardRequest;
use App\Http\Requests\ChangeSubscriptionPlanRequest;
use Log;
use DB;

class SubscriptionController extends Controller {
    private $response = [];
    
    public function __construct(){
        $this->middleware('auth');
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
    }

    public function getSubscription(){
        $recruiter = RecruiterProfile::where(['user_id' => Auth::user()->id])->first();
        if($recruiter['is_subscribed'] == config('constants.ZeroValue')){
            $result = view('web.subscription',['activeTab'=>'4']);
        }else{
            $result = redirect('jobtemplates');
        }
        return $result;
    }
    
    public function getSubscriptionList(){
        $recruiterOffice = RecruiterOffice::getAllOffices();
        $subscription['data'] = [];
        $customerId = RecruiterProfile::where(['user_id' => Auth::user()->id])->pluck('customer_id');
        if($customerId[0] == null){
            $subscription['customer'] = [];
        }else{
            $customer = \Stripe\Customer::retrieve($customerId[0]);
            $subscription['customer'] = [$customer];
        }
        foreach ($recruiterOffice as $office){
            if($office['free_trial_period'] != config('constants.ZeroValue')){
                $subscription['data'] = $office;
                break;
            }
        }
        return $subscription;
    }
    
    public function postCreateSubscription(CreateSubscriptionRequest $request){
        try{
            DB::beginTransaction();
            $recruiter = RecruiterProfile::where(['user_id' => Auth::user()->id])->first();
            if($request->cardExist === "true"){
                $createSubscription = $this->addUserTOSubscription($recruiter['customer_id'], $request->subscriptionType, $request->trailPeriod);
                RecruiterProfile::where(['user_id' => Auth::user()->id])->update(['is_subscribed' => config('constants.OneValue'), 'free_period' => $request->trailPeriod]);
                $this->response['success'] = true;
                $this->response['message'] = trans('messages.user_subscribed');
            }else{
                if($recruiter['customer_id'] == null){
                    $createCustomer = $this->createCustomer();
                    if($createCustomer['success'] == true){
                        $customer = $createCustomer['data']['id'];
                    }else{
                        $customer = null;
                        $this->response['success'] = false;
                        $this->response['message'] = $createCustomer['message'];
                        $this->response['data'] = null;
                    }
                }else{
                    $customer = $recruiter['customer_id'];
                }
                if($customer != null){
                    $addCard = $this->addCardForSubscription($request->all(), $customer);
                    if($addCard['success'] == true){
                        $createSubscription = $this->addUserTOSubscription($customer, $request->subscriptionType, $request->trailPeriod);
                        RecruiterProfile::where(['user_id' => Auth::user()->id])->update(['is_subscribed' => config('constants.OneValue'), 'free_period' => $request->trailPeriod]);
                        $this->response['success'] = true;
                        $this->response['message'] = trans('messages.user_subscribed');
                    }else{
                        $this->response['success'] = false;
                        $this->response['data'] = null;
                        $this->response['message'] = $addCard['message'];
                    }
                }else{
                    $this->response['success'] = false;
                    $this->response['data'] = null;
                    $this->response['message'] = trans('messages.cannot_subscribe');
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            $this->response['success'] = false;
            $this->response['data'] = null;
            $this->response['message'] = $e->getMessage();
        }
        return $this->response;
    }
    
    private function addUserTOSubscription($customerId, $subscriptionType, $trailPeriod){
        try{
            $now = \Carbon\Carbon::now();
            $fotDiff = \Carbon\Carbon::now();
            $addMonths = $now->addMonths($trailPeriod);
            $trailPeriodDays = $addMonths->diff($fotDiff)->days;
            //dd($trailPeriodDays);
            if($subscriptionType == 1){
                $planId = config('constants.SixMonths');
            }else{
                $planId = config('constants.OneYear');
            }
            $subscription = \Stripe\Subscription::create(array(
                "customer" => $customerId,
                "plan" => $planId,
                "trial_period_days" => $trailPeriodDays
            ));
            $this->saveSubscription($subscription);
            $this->response['success'] = true;
            $this->response['message'] = trans('messages.user_added_to_subscription');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $this->response['success'] = false;
            $this->response['message'] = $e->getMessage();
        }
        return $this->response;
    }
    
    public function saveSubscription($subscription){
        $payments = new SubscriptionPayments;
        $payments->recruiter_id=Auth::user()->id;
        $payments->expiryDate = date('Y-m-d', $subscription['current_period_end']);
        $payments->trialEnd = date('Y-m-d', $subscription['trial_end']);
        $payments->paymentId = $subscription['id'];
        $payments->paymentResponse = json_encode($subscription);
        $payments->save();
    }


    public function postAddCard(AddCardRequest $request){
        try{
            $customerId = RecruiterProfile::where(['user_id' => Auth::user()->id])->pluck('customer_id');
            $expiry = explode('/', $request->expiry);
            $month = $expiry[0];
            $year = $expiry[1];
            $cardToken = \Stripe\Token::create(array(
                            "card" => array(
                              "number" => $request->cardNumber,
                              "exp_month" => $month,
                              "exp_year" => $year,
                              "cvc" => $request->cvv
                            )
                          ));
            if(isset($cardToken['id'])){
                $customer = \Stripe\Customer::retrieve($customerId[0]);
                $customer->sources->create(array(
                    "source" => $cardToken['id']
                ));
                $this->response['success'] = true;
                $this->response['message'] = trans('messages.card_added');
            }else{
                $this->response['success'] = false;
                $this->response['data'] = null;
                $this->response['message'] = trans('messages.cannot_add_card');
            }
        } catch (\Exception $e) {
            Log::error($e);
            $this->response['success'] = false;
            $this->response['message'] = $e->getMessage();
        }
        return $this->response;
    }
    
    private function addCardForSubscription($cardDetails, $customerId){
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
            if(isset($cardToken['id'])){
                $customer = \Stripe\Customer::retrieve($customerId);
                $customer->sources->create(array(
                    "source" => $cardToken['id']
                ));
                $this->response['success'] = true;
                $this->response['message'] = trans('messages.card_added');
            }else{
                $this->response['success'] = false;
                $this->response['data'] = null;
                $this->response['message'] = trans('messages.cannot_add_card');
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $this->response['success'] = false;
            $this->response['data'] = null;
            $this->response['message'] = $e->getMessage();
        }
        return $this->response;
    }
    
    private function createCustomer(){
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
            Log::error($e->getMessage());
            $this->response['success'] = false;
            $this->response['message'] = $e->getMessage();
        }
        return $this->response;
    }
    
    public function getSettingSubscription(){
        return view('web.setting-subscription',['activeTab'=>'4']);
    }
    
    public function getSubscriptionDetails(){
        try{
            $subscriptions = $this->fetchSubscription(Auth::user()->id);
            $this->response['success'] = true;
            $this->response['data'] = $subscriptions;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $this->response['success'] = false;
            $this->response['message'] = $e->getMessage();
        }
        return $this->response;
    }
    
    private function fetchSubscription($userId){
        try{
            $customerId = RecruiterProfile::where(['user_id' => $userId])->pluck('customer_id');
            $customerWithSubscription = $this->fetchUser($customerId[0]);
            if($customerWithSubscription['success'] == false){
                $this->response['success'] = false;
                $this->response['data'] = null;
                $this->response['message'] = trans('messages.no_customer');
            }else{
                $this->response['success'] = true;
                $this->response['data'] = $customerWithSubscription;
                $this->response['message'] = trans('messages.customer_fetched');
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $this->response['success'] = false;
            $this->response['message'] = $e->getMessage();
        }
        return $this->response;
    }
    
    private function fetchUser($customerId){
        try{
            $customer = \Stripe\Customer::retrieve($customerId);
            foreach($customer->subscriptions['data'] as $subscription){
                $subscription['created'] = date('Y-m-d', $subscription['created']);
                $subscription['current_period_end'] = date('Y-m-d', $subscription['current_period_end']);
                
            }
            $this->response['success'] = true;
            $this->response['data'] = $customer;
            $this->response['message'] = trans('messages.customer_fetched');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $this->response['success'] = false;
            $this->response['message'] = $e->getMessage();
        }
        return $this->response;
    }
    
    public function postDeleteCard(DeleteCardRequest $request){
        try{
            $customerId = RecruiterProfile::where(['user_id' => Auth::user()->id])->pluck('customer_id');
            $customer = \Stripe\Customer::retrieve($customerId[0]);
            if(count($customer->sources['data']) > 1){
                if($customer->sources->retrieve($request->cardId)->delete()){
                    $this->response['success'] = true;
                    $this->response['message'] = trans('messages.card_deleted');
                }else{
                    $this->response['success'] = false;
                    $this->response['message'] = trans('messages.no_card');
                }
            }else{
                $this->response['success'] = false;
                $this->response['message'] = trans('messages.cannot_delete_card');
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $this->response['success'] = false;
            $this->response['message'] = $e->getMessage();
        }
        return $this->response;
    }
    
    public function postUnsubscribe(UnsubscribeRequest $request){
        try{
            $sub = \Stripe\Subscription::retrieve($request->subscriptionId);
            if($sub->cancel(array("at_period_end" => true ))){
                RecruiterProfile::where(['user_id' => Auth::user()->id])->update(['is_subscribed' => config('constants.OneValue'), 'free_period' => null, 'auto_renewal' => null]);
                $this->response['success'] = true;
                $this->response['message'] = trans('messages.unsubscribed');
            }else{
                $this->response['success'] = false;
                $this->response['message'] = trans('messages.no_subscription');
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $this->response['success'] = false;
            $this->response['message'] = trans('messages.no_subscription');
        }
        return $this->response;
    }
    
    public function postEditCard(EditCardRequest $request){
        try{
            $expiry = explode('/', $request->expiry);
            $month = $expiry[0];
            $year = $expiry[1];
            $customerId = RecruiterProfile::where(['user_id' => Auth::user()->id])->pluck('customer_id');
            $customer = \Stripe\Customer::retrieve($customerId[0]);
            $card = $customer->sources->retrieve($request->cardId);
            $card->exp_month = $month;
            $card->exp_year = $year;
            $card->save();
            $this->response['success'] = true;
            $this->response['message'] = trans('messages.card_edidted');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $this->response['success'] = false;
            $this->response['message'] = $e->getMessage();
        }
        return $this->response;
    }
    
    public function postChangeSubscriptionPlan(ChangeSubscriptionPlanRequest $request){
        try{
            $plan = config('constants.SixMonths');
            if($request->plan == 2){
                $plan = config('constants.OneYear');
            }
            $subscription = \Stripe\Subscription::retrieve($request->subscriptionId);
            $subscription->plan = $plan;
            $subscription->save();
            if($request->type == config('constants.Resubscribe')){
                RecruiterProfile::where(['user_id' => Auth::user()->id])->update(['is_subscribed' => config('constants.OneValue')]);
            }
            $this->response['success'] = true;
            $this->response['message'] = trans('messages.subscription_plan_changed');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $this->response['success'] = true;
            $this->response['message'] = $e->getMessage();
        }
        return $this->response;
    }
}
