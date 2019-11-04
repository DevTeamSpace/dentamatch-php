<?php

namespace App\Http\Controllers\web;

use App\Helpers\WebResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\CheckPromoCodeRequest;
use App\Models\PromoCode;
use App\Utils\ActionLogUtils;
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
     * Method to view subscription plans to create new subscription
     * GET /subscription-detail
     * @return Response
     */
    public function getSubscription()
    {
        $recruiter = RecruiterProfile::current();
        if ($recruiter->subscribed()) {
            return redirect('jobtemplates');
        }
        if ($recruiter->offices()->count() === 0) {
            return redirect('home');
        }
        return view('web.subscription');
    }

    /**
     * Method to get subscription plans
     * GET AJAX /get-subscription-list from subscription.js
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
     * POST AJAX /create-subscription from subscription.js
     * @param CreateSubscriptionRequest $request
     * @return JsonResponse
     */
    public function postCreateSubscription(CreateSubscriptionRequest $request)
    {
        $user = Auth::user();
        $recruiter = $user->recruiterProfile;

        if (!$recruiter->stripe_id) {
            $stripeCustomer = \Stripe\Customer::create([
                "description" => "Customer for " . $user->email,
                "email"       => $user->email
            ]);
            $recruiter->stripe_id = $stripeCustomer->id;
            $recruiter->save();
        } else {
            $stripeCustomer = \Stripe\Customer::retrieve($recruiter->stripe_id);
        }

        // todo check code here
        $codeModel = PromoCode::query()->where('active', 1)->where('code', $request->promoCode)->first();
        $paymentNeeded = !$codeModel || !($codeModel->free_days || $codeModel->access_until);

        if ($paymentNeeded && $stripeCustomer->sources->total_count === 0) {
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

        $planId = $this->stripeUtils->getPlanId($request->subscriptionType);
        $createData = [
            'plan'              => $planId,
            'trial_from_plan'   => false,
            'metadata'          => ['promo' => object_get($codeModel, 'code', '')]
        ];

        if ($trialDays = object_get($codeModel, 'free_days', 0))
            $createData['trial_period_days'] = $trialDays;

        if ($trialEnds = object_get($codeModel, 'access_until')) {
            $trialEnds = (new Carbon($trialEnds))->addDay(1);
            $createData['trial_end'] = $trialEnds->timestamp;
        }

        $stripeSubscription = $stripeCustomer->subscriptions->create($createData);

        $subscription = $recruiter->subscriptions()->create([
            'name'          => 'default',
            'stripe_id'     => $stripeSubscription->id,
            'stripe_plan'   => $planId,
            'quantity'      => 1,
            'trial_ends_at' => $stripeSubscription->trial_end ? Carbon::createFromTimestampUTC($stripeSubscription->trial_end)
                ->toDateTimeString() : null,
            'ends_at'       => null,
        ]);

        if ($codeModel)
            $user->codes()->attach($codeModel->id, ['subscription_id' => $subscription->id]);

        return WebResponse::dataResponse($stripeSubscription->id);
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
        } catch (InvalidRequest $e) {
            return WebResponse::successResponse();
        }

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
        $recruiterSubscription->swap($plan);
        return WebResponse::successResponse(trans('messages.subscription_plan_changed'));
    }

    /**
     * Get promo code data
     * POST AJAX /check-promo-code from subscription.js
     * @param CheckPromoCodeRequest $request
     * @return JsonResponse
     */
    public function postCheckPromoCode(CheckPromoCodeRequest $request)
    {
        ActionLogUtils::logRecruiterCheckPromoCode($request->promoCode);

        $codeModel = PromoCode::query()->where('active', 1)->where('code', $request->promoCode)->first();
        if (!$codeModel)
            return WebResponse::errorResponse("Code '$request->promoCode' not found");

        if ($codeModel->valid_until && $codeModel->valid_until < Carbon::today()->toDateString())
            return WebResponse::errorResponse("Code '$request->promoCode' has expired");

        if ($codeModel->valid_days_from_sign_up &&
            Auth::user()->created_at->addDays($codeModel->valid_days_from_sign_up) < Carbon::today()->toDateString())
            return WebResponse::errorResponse("Code '$request->promoCode' has expired");

        return WebResponse::dataResponse([
            'code'         => $codeModel->code,
            'subscription' => $codeModel->subscription,
            'text'         => $codeModel->name,
            'noPayment'    => $codeModel->free_days > 0 || $codeModel->access_until
        ]);

    }

}
