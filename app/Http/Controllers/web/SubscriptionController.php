<?php

namespace App\Http\Controllers\web;

use App\Helpers\WebResponse;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\RecruiterProfile;
use App\Models\RecruiterOffice;
use App\Http\Requests\CreateSubscriptionRequest;
use App\Http\Requests\AddCardRequest;
use App\Http\Requests\DeleteCardRequest;
use App\Http\Requests\UnsubscribeRequest;
use App\Http\Requests\EditCardRequest;
use App\Http\Requests\ChangeSubscriptionPlanRequest;
use Laravel\Cashier\Subscription;
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
            'cardExist'     => false,
            'isNewCustomer' => $recruiter->subscriptions->isEmpty(),
            'supported'     => $offices->isNotEmpty()
        ];

        if ($recruiter->hasStripeId()) {
            try {
                $subscription['cardExist'] = $recruiter->cards()->isNotEmpty();
            } catch (InvalidRequest $e) {
            }
        }
        return $subscription;
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
        if (!$recruiter->stripe_id) {
            $stripeCustomer = \Stripe\Customer::create([
                "description" => "Customer for " . Auth::user()->email,
                "email"       => Auth::user()->email
            ]);
            $recruiter->stripe_id = $stripeCustomer->id;
            $recruiter->save();
        } else {
            $stripeCustomer = \Stripe\Customer::retrieve($recruiter->stripe_id);
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
        $planId = $this->stripeUtils->getPlanId($request->subscriptionType);

        $subscription = $stripeCustomer->subscriptions->create([
            'plan'            => $planId,
            'trial_from_plan' => $isNewCustomer
        ]);

        $recruiter->subscriptions()->create([
            'name'          => 'default',
            'stripe_id'     => $subscription->id,
            'stripe_plan'   => $planId,
            'quantity'      => 1,
            'trial_ends_at' => $subscription->trial_end ? Carbon::createFromTimestampUTC($subscription->trial_end)
                ->toDateTimeString() : null,
            'ends_at'       => null,
        ]);

        return WebResponse::dataResponse($subscription->id);
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
            $customer = RecruiterProfile::current()->asStripeCustomer();
//            $customer->s
        } catch (InvalidRequest $e) {
            return WebResponse::successResponse();
        }

//        foreach ($customer->subscriptions['data'] as $subscription) {
//            if ($subscription['trial_end'] != null && $subscription['current_period_end'] == $subscription['trial_end']) {
//                $trialEnd = date('Y-m-d', $subscription['trial_end']);
//                $subscription['current_period_end'] = strtotime($trialEnd . '+ ' . $subscription['plan']['interval_count'] . ' ' . $subscription['plan']['interval']);
//            }
//        }
        return WebResponse::dataResponse($customer);
    }


    /**
     * Method to add card
     * POST AJAX /add-card from setting-subscription.js
     * @param AddCardRequest $request
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
        $customer = $recruiter->asStripeCustomer();
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
        $customer = $recruiter->asStripeCustomer();
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
        /** @var Subscription $subscription */
        $subscription = RecruiterProfile::current()->subscriptions()->where('stripe_id', $request->subscriptionId)->firstOrFail();
        $subscription->cancel();
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
        $customer = RecruiterProfile::current()->asStripeCustomer();
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
        $recruiterSubscription = RecruiterProfile::current()->subscriptions()->where('stripe_id', $request->subscriptionId)->firstOrFail();
        $plan = $this->stripeUtils->getPlanIdByNickname($request->plan);
        $recruiterSubscription->skipTrial()->swap($plan);

//        $subscription = \Stripe\Subscription::retrieve($recruiterSubscription->subscription_id);
//        $subscription->plan = $plan;
//        $subscription->trial_end = "now";
//        $subscription->save();
//        RecruiterProfile::whereUserId($recruiterSubscription->recruiter_id)->update(['is_subscribed' => 1]);
//        $recruiterSubscription->update(['cancel_at_period_end' => false]);
        return WebResponse::successResponse(trans('messages.subscription_plan_changed'));
    }

}
