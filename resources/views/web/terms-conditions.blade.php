@extends('web.layouts.signup')

@section('content')
  <div class="container">
    <div class="frm-cred-access-box bg-white terms-box-outer">
      <div class="terms-box">
        <div class="scrollable_div">
          <div class="frm-title mr-b-40">Terms &amp; Conditions</div>
          <div class="child_scrollable_div pd-0">
            <p class="mr-t-30">Last updated: May 16, 2018</p>
            <p class="mr-t-30">Please read these Terms and Conditions ("Terms", "Terms and Conditions") carefully before
              using the www.dentamatch.co website and the
              DentaMatch mobile application (together, or individually, the "Service") operated by Health Match Inc.
              ("us", "we", or "our").</p>
            <p class="mr-t-30">Your access to and use of the Service is conditioned upon your acceptance of and
              compliance with these Terms. These Terms apply to all
              visitors, users and others who wish to access or use the Service.</p>
            <p class="mr-t-30">By accessing or using the Service you agree to be bound by these Terms. If you disagree
              with any part of the terms then you do not have
              permission to access the Service.</p>
            <h4 class="pg-heading mr-t-30 mr-b-30">Communications</h4>
            <p class="mr-t-30">By creating an Account on our service, you agree to subscribe to newsletters, marketing
              or promotional materials and other information we
              may send. However, you may opt out of receiving any, or all, of these communications from us by following
              the unsubscribe link or
              instructions provided in any email we send.</p>
            <h4 class="pg-heading mr-t-30 mr-b-30">Purchases</h4>
            <p class="mr-t-30">If you wish to purchase any product or service made available through the Service
              ("Purchase"), you may be asked to supply certain
              information relevant to your Purchase including, without limitation, your credit card number, the
              expiration date of your credit card, your
              billing address, and your shipping information.</p>
            <p class="mr-t-30">You represent and warrant that: (i) you have the legal right to use any credit card(s) or
              other payment method(s) in connection with any
              Purchase; and that (ii) the information you supply to us is true, correct and complete.</p>
            <p class="mr-t-30">The service may employ the use of third party services for the purpose of facilitating
              payment and the completion of Purchases. By
              submitting your information, you grant us the right to provide the information to these third parties
              subject to our Privacy Policy.</p>
            <p class="mr-t-30">We reserve the right to refuse or cancel your order at any time for reasons including but
              not limited to: product or service availability, errors
              in the description or price of the product or service, error in your order or other reasons.</p>
            <p class="mr-t-30">We reserve the right to refuse or cancel your order if fraud or an unauthorized or
              illegal transaction is suspected.</p>
            <h4 class="pg-heading mr-t-30 mr-b-30">Availability, Errors and Inaccuracies</h4>
            <p class="mr-t-30">We are constantly updating product and service offerings on the Service. We may
              experience delays in updating information on the Service
              and in our advertising on other web sites. The information found on the Service may contain errors or
              inaccuracies and may not be complete
              or current. Products or services may be mispriced, described inaccurately, or unavailable on the Service
              and we cannot guarantee the
              accuracy or completeness of any information found on the Service.</p>
            <p class="mr-t-30">We therefore reserve the right to change or update information and to correct errors,
              inaccuracies, or omissions at any time without prior
              notice.</p>
            <h4 class="pg-heading mr-t-30 mr-b-30">Contests, Sweepstakes and Promotions</h4>
            <p class="mr-t-30">Any contests, sweepstakes or other promotions (collectively, "Promotions") made available
              through the Service may be governed by rules
              that are separate from these Terms Conditions. If you participate in any Promotions, please review the
              applicable rules as well as our Privacy
              Policy. If the rules for a Promotion conflict with these Terms and Conditions, the Promotion rules will
              apply.</p>
            <h4 class="pg-heading mr-t-30 mr-b-30">Subscriptions</h4>
            <p class="mr-t-30">Some parts of the Service are billed on a subscription basis ("Subscription(s)"). You
              will be billed in advance on a recurring and periodic
              basis ("Billing Cycle"). Billing cycles are set either on a monthly, semi-annual, or annual basis,
              depending on the type of subscription plan
              you select when purchasing a Subscription.</p>
            <p class="mr-t-30">At the end of each Billing Cycle, your Subscription will automatically renew under the
              exact same conditions unless you cancel it or Health
              Match Inc. cancels it. You may cancel your Subscription renewal either through your online account
              management page or by contacting
              Health Match Inc. customer support team.</p>
          </div>
        </div>
      </div>
      <div class="terms-btn text-right">
        <a type="button" href="{{url('logout')}}" class="btn btn-link mr-r-40">Decline</a>

        <a href="tutorial" class="btn btn-primary pd-l-30 pd-r-30">Accept</a>
      </div>
    </div>
  </div>
@endsection