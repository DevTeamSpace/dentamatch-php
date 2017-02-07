@extends('web.layouts.signup')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container" id="subscription">
    <div class="frm-cred-access-box subscription-box" data-bind="visible: visibleSubcription">
        <h4 class="frm-title">Our Subscription Plan</h4>
        <p>Unlock unlimited access to jobs post and finding suitable jobseekers.</p>
        <div class="subs-holder text-center ">
            <!--ko foreach: subscriptionDetails-->
            <div class="subscription-inr-box ">
                <div class="subscription-type">
                    <p class="mr-b-25">Half Yearly</p>
                    <div class="subcription-price pos-rel">
                        <span class="price-symbol ">$</span>
                        <span class="price">60</span>
                        <span class="price-duration">/ 6 mo.</span>
                        <p data-bind="text: free_trial_period">with 1 months free trial</p>
                    </div>
                </div>
                <div class="subscription-desc">
                    <p>Unlimited template creation,
                        job posting, searching jobseeker, message & reports</p>
                </div>
                <a id="stripe" href="https://connect.stripe.com/oauth/authorize?response_type=code&client_id=ca_A4GIpAptEF5hAp1QDsIVsSgNcF4P1QcV&scope=read_write&page=subscription_list" class="btn btn-primary pd-l-10 pd-r-10 mr-t-20 mr-b-20">Get Started</a>
            </div>

            <!--/ko-->
<!--            <div class="subscription-inr-box ">
                <div class="subscription-type">
                    <p class="mr-b-25">Annual</p>
                    <div class="subcription-price pos-rel">
                        <span class="price-symbol ">$</span>
                        <span class="price">99</span>
                        <span class="price-duration">/ yr.</span>
                        <p>with 2 months free trial</p>
                    </div>
                </div>
                <div class="subscription-desc">
                    <p>Unlimited template creation,
                        job posting, searching jobseeker, message & reports</p>
                </div>
                <a class="btn btn-primary pd-l-10 pd-r-10 mr-t-20 mr-b-20">Get Started</a>
            </div>-->




        </div>
    </div>
    <div class="frm-cred-access-box subscription-box" data-bind="visible: noSubscription">
        <h3 class="no-subscription-heading text-center" data-bind="text: noSubscriptionDetails"></h3>
    </div>

</div>

@endsection
@section('js')
<script type="text/javascript" src="{{asset('web/scripts/knockout-3.4.1.js')}}"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
var FirstSubscriptionVM = function () {
    var me = this;
    me.isLoading = ko.observable(false);
    me.visibleSubcription = ko.observable(false);
    me.subscriptionDetails = ko.observableArray([]);
    me.noSubscription = ko.observable(false);
    me.noSubscriptionDetails = ko.observable('');
    
    me.getSubscriptionList = function () {
        if (me.isLoading()) {
            return false;
        }
        me.isLoading(true);
        $.get('get-subscription-list', {}, function (d) {
            if(typeof d.data != "undefined"){
                d.data['free_trial_period'] = 'with '+d.data['free_trial_period']+' months free trial';
                me.visibleSubcription(true);
                me.subscriptionDetails.push(d.data);
            }else{
                me.visibleSubcription(false);
                me.noSubscription(true);
                me.noSubscriptionDetails('We are not provideing service in your area, we will notifiy you whenever we will start our services in your area.');
            }
        }).error(function (xhr, e) {
            me.isLoading(false);
        });
    };
    
    me._init = function () {
        me.getSubscriptionList();
    };
    me._init();
};
var fsObj = new FirstSubscriptionVM();
ko.applyBindings(fsObj, $('#subscription')[0])
</script>
@endsection
