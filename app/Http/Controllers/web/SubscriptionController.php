<?php

namespace App\Http\Controllers\web;

use App\Helpers\WebResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\RecruiterProfile;
use App\Models\RecruiterOffice;
use App\Models\Subscription;
use App\Http\Requests\CreateSubscriptionRequest;
use App\Http\Requests\AddCardRequest;
use App\Http\Requests\DeleteCardRequest;
use App\Http\Requests\UnsubscribeRequest;
use App\Http\Requests\EditCardRequest;
use App\Http\Requests\ChangeSubscriptionPlanRequest;
use Stripe\Error\InvalidRequest;
use App\Utils\StripeUtils;

class SubscriptionController extends Controller
{
    private $stripeUtils;

    /**
     * SubscriptionController constructor.
     * @param StripeUtils $stripe
     */
    public function __construct(StripeUtils $stripe)
    {
        $this->middleware('auth');
        $this->stripeUtils = $stripe;
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Method to view subscription plans to crete first subscription
     * GET /subscription-detail
     * @return Response
     */
    public function getSubscription()
    {
        $recruiter = RecruiterProfile::current();
        if ($recruiter->is_subscribed) {
            return redirect('jobtemplates');
        }
        if ($recruiter->offices()->count() === 0) {
            return redirect('home');
        }
        return view('web.subscription');
    }

    /**
     * Method to get subscription plans
     * GET AJAX /get-subscription-list from subscriptions.js
     * @return array
     */
    public function getSubscriptionList()
    {
        $recruiter = RecruiterProfile::current();
        $offices = RecruiterOffice::getActiveOffices();
        $subscription = [
            'cardExist' => false,
            'isNewCustomer' => $recruiter->subscriptions->isEmpty(),
            'supported' => $offices->isNotEmpty()
        ];

        if ($recruiter->customer_id) {
            try {
                $customer = \Stripe\Customer::retrieve($recruiter->customer_id);
                $subscription['cardExist'] = $customer->sources->total_count;
            } catch (InvalidRequest $e) {}
        }
        return $subscription;
    }

    /**
     * Method to view subscription page in settings
     * GET /setting-subscription
     * @return Response
     */
    public function getSettingSubscription()
    {
        return view('web.setting-subscription', ['activeTab' => '4']);
    }

    /**
     * Method to get subscription details
     * GET AJAX /get-subscription-details from setting-subscription.js
     * @return JsonResponse
     */
    public function getSubscriptionDetails()
    {
        try {
            $customer = \Stripe\Customer::retrieve(RecruiterProfile::current()->customer_id);
        } catch (InvalidRequest $e) {
            return WebResponse::successResponse();
        }

        foreach ($customer->subscriptions['data'] as $subscription) {
            if ($subscription['trial_end'] != null && $subscription['current_period_end'] == $subscription['trial_end']) {
                $trialEnd = date('Y-m-d', $subscription['trial_end']);
                $subscription['current_period_end'] = strtotime($trialEnd . '+ ' . $subscription['plan']['interval_count'] . ' ' . $subscription['plan']['interval']);
            }
        }
        return WebResponse::dataResponse($customer);
    }

    /**
     * Method to create subscription
     * POST AJAX /create-subscription from subscriptions.js
     * @param CreateSubscriptionRequest $request
     * @return JsonResponse
     */
    public function postCreateSubscription(CreateSubscriptionRequest $request)
    {
        $recruiter = RecruiterProfile::current();
        if (!$recruiter->customer_id) {
            $stripeCustomer = \Stripe\Customer::create([
                "description" => "Customer for " . Auth::user()->email,
                "email"       => Auth::user()->email
            ]);
            $recruiter->customer_id = $stripeCustomer->id;
            $recruiter->save();
        } else {
            $stripeCustomer = \Stripe\Customer::retrieve($recruiter->customer_id);
        }

        if ($stripeCustomer->sources->total_count === 0) {
            $expiry = explode('/', $request->input('expiry', ''));
            $month = array_get($expiry, '0');
            $year = array_get($expiry, '1');
            $cardToken = \Stripe\Token::create([
                "card" => [
                    "number"    => $request->input('cardNumber', ''),
                    "exp_month" => $month,
                    "exp_year"  => $year,
                    "cvc"       => $request->input('cvv', '')
                ]
            ]);
            $stripeCustomer->sources->create([
                "source" => $cardToken['id']
            ]);
        }

        $isNewCustomer = $recruiter->subscriptions->isEmpty();
        $subId = $this->addUserToSubscription($recruiter->customer_id, $request->subscriptionType, $isNewCustomer);
        $recruiter->is_subscribed = 1;
        $recruiter->save();
        return WebResponse::dataResponse($subId);
    }




    /**
     * Method to add card
     * POST AJAX /add-card from setting-subscription.js
     * @return JsonResponse
     */
    public function postAddCard(AddCardRequest $request)
    {
        $recruiter = RecruiterProfile::current();
        $expiry = explode('/', $request->input('expiry', ''));
        $cardToken = \Stripe\Token::create([
            "card" => [
                "number"    => $request->input('cardNumber', ''),
                "exp_month" => array_get($expiry, '0'),
                "exp_year"  => array_get($expiry, '1'),
                "cvc"       => $request->input('cvv', '')
            ]
        ]);
        $customer = \Stripe\Customer::retrieve($recruiter->customer_id);
        $customer->sources->create([
            "source" => $cardToken['id']
        ]);

        return WebResponse::successResponse(trans('messages.card_added'));
    }


    /**
     * Method to delete card from stripe
     * POST AJAX /delete-card from subscription-setting.js
     * @param DeleteCardRequest $request
     * @return JsonResponse
     */
    public function postDeleteCard(DeleteCardRequest $request)
    {
        $recruiter = RecruiterProfile::current();
        $customer = \Stripe\Customer::retrieve($recruiter->customer_id);
        if (count($customer->sources['data']) > 1) {
            $customer->sources->retrieve($request->cardId)->delete();
            return WebResponse::successResponse(trans('messages.card_deleted'));
        } else {
            return WebResponse::errorResponse(trans('messages.cannot_delete_card'));
        }
    }

    /**
     * Method to unsubscribe
     * POST AJAX /unsubscribe from subscription-setting.js
     * @param UnsubscribeRequest $request
     * @return JsonResponse
     */
    public function postUnsubscribe(UnsubscribeRequest $request)
    {
        $subscription = RecruiterProfile::current()->subscriptions()->where('subscription_id', $request->subscriptionId)->firstOrFail();
        $sub = \Stripe\Subscription::retrieve($subscription->subscription_id);
        $sub->cancel(['at_period_end' => true]);
        $subscription->update(['cancel_at_period_end' => true]);
        return WebResponse::successResponse();
    }

    /**
     * Method to edit card's expiry date
     * POST AJAX /edit-card from setting-subscription.js
     * @param EditCardRequest $request
     * @return JsonResponse
     */
    public function postEditCard(EditCardRequest $request)
    {
        $expiry = explode('/', $request->input('expiry', ''));
        $recruiter = RecruiterProfile::current();
        $customer = \Stripe\Customer::retrieve($recruiter->customer_id);
        $card = $customer->sources->retrieve($request->cardId);
        $card->exp_month = array_get($expiry, '0');
        $card->exp_year = array_get($expiry, '1');
        $card->save();
        return WebResponse::successResponse(trans('messages.card_edidted'));
    }

    /**
     * Method to change subscription plan or resubscribe
     * POST AJAX /change-subscription-plan from setting-subscription.js
     * @param ChangeSubscriptionPlanRequest $request
     * @return JsonResponse
     */
    public function postChangeSubscriptionPlan(ChangeSubscriptionPlanRequest $request)
    {
        /** @var Subscription $recruiterSubscription */
        $recruiterSubscription = RecruiterProfile::current()->subscriptions()->where('subscription_id', $request->subscriptionId)->firstOrFail();
        $plan = $this->stripeUtils->getPlanIdByNickname($request->plan);
        $subscription = \Stripe\Subscription::retrieve($recruiterSubscription->subscription_id);
        $subscription->plan = $plan;
        $subscription->trial_end = "now";
        $subscription->save();
        RecruiterProfile::whereUserId($recruiterSubscription->recruiter_id)->update(['is_subscribed' => 1]);
        $recruiterSubscription->update(['cancel_at_period_end' => false]);
        return WebResponse::successResponse(trans('messages.subscription_plan_changed'));
    }

    /**
     * Method to add user to subscription
     * @param $customerId
     * @param $subscriptionType
     * @param $useTrialAndDiscount
     * @return string
     */
    private function addUserToSubscription($customerId, $subscriptionType, $useTrialAndDiscount)
    {
        $planId = $this->stripeUtils->getPlanId($subscriptionType);
        $subscription = \Stripe\Subscription::create([
            "customer"        => $customerId,
            "plan"            => $planId,
            "trial_from_plan" => $useTrialAndDiscount
        ]);
        $payments = new Subscription();
        $payments->recruiter_id = Auth::user()->id;
        $payments->subscription_expiry_date = date('Y-m-d H:i:s', $subscription['current_period_end']);
        $payments->trial_end = $payments->subscription_expiry_date;
        if ($subscription['trial_end'] != null) {
            $payments->trial_end = date('Y-m-d H:i:s', $subscription['trial_end']);
        }
        $payments->subscription_id = $subscription['id'];
        $payments->subscription_response = json_encode($subscription);
        $payments->save();
        return $subscription->id;
    }
}
