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
                        <span class="price" data-bind="text: halfYearPrice">60</span>
                        <span class="price-duration">/ 6 mo.</span>
                        <p data-bind="text: free_trial_period">with 1 months free trial</p>
                        <input type="hidden" id="stype" value="1">
                    </div>
                </div>
                <div class="subscription-desc">
                    <p>Unlimited template creation,
                        job posting, searching jobseeker, message & reports</p>
                </div>
                <!--<a id="stripe" href="https:/connect.stripe.com/oauth/authorize?response_type=code&client_id=ca_A4GIpAptEF5hAp1QDsIVsSgNcF4P1QcV&scope=read_write&page=subscription_list" class="btn btn-primary pd-l-10 pd-r-10 mr-t-20 mr-b-20">Get Started</a>-->
                <a id="stripe" data-bind="click: $root.addCard" class="btn btn-primary pd-l-10 pd-r-10 mr-t-20 mr-b-20">Get Started</a>
            </div>
            
            <div class="subscription-inr-box ">
                <div class="subscription-type">
                    <p class="mr-b-25">Yearly</p>
                    <div class="subcription-price pos-rel">
                        <span class="price-symbol ">$</span>
                        <span class="price" data-bind="text: fullYearPrice">99</span>
                        <span class="price-duration">/ 1 year.</span>
                        <p data-bind="text: free_trial_period">with 1 months free trial</p>
                        <input type="hidden" id="stype" value="2">
                    </div>
                </div>
                <div class="subscription-desc">
                    <p>Unlimited template creation,
                        job posting, searching jobseeker, message & reports</p>
                </div>
                <!--<a id="stripe" href="https:/connect.stripe.com/oauth/authorize?response_type=code&client_id=ca_A4GIpAptEF5hAp1QDsIVsSgNcF4P1QcV&scope=read_write&page=subscription_list" class="btn btn-primary pd-l-10 pd-r-10 mr-t-20 mr-b-20">Get Started</a>-->
                <a id="stripe" data-bind="click: $root.addCard" class="btn btn-primary pd-l-10 pd-r-10 mr-t-20 mr-b-20">Get Started</a>
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
    <div class="frm-cred-access-box subscription-box" data-bind="visible: noSubscription">
        <h3 class="no-subscription-heading text-center" data-bind="text: noSubscriptionDetails"></h3>
    </div>
</div>
    <div id="addCardModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog custom-modal modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-bind="visible: cancelButton"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title">Add Card</h4>
                </div>
                <form id="addCardForm" data-bind="submit: $root.addCardFunction">
                <div class="modal-body">
                  <p class="text-center">Please provide card details to subscribe.</p>
                  <p class="text-center" style="color: blue" data-bind="text: creatingMessage"></p>
                  <p class="text-center" style="color: red" data-bind="text: errorMessage"></p>
                  <p class="text-center" style="color: green;" data-bind="text: successMessage"></p>
                    <div class="form-group">
                        <label class="sr-only" for="card-number">Card number</label>
                        <input type="number" class="form-control" id="card-number" placeholder="Card number" data-bind="value: cardNumber, disable: disableInput">
                    </div>
                    <div class="form-group">
                        <label class="sr-only" for="expiry">Expiry</label>
                        <input type="text" class="form-control" id="expiry" placeholder="MM/YY" data-bind="value: expiry, disable: disableInput">
                    </div>
                    <div class="form-group">
                        <label class="sr-only" for="cvv">CVV</label>
                        <input type="number" class="form-control" id="cvv" placeholder="CVV" data-bind="value: cvv, disable: disableInput">
                    </div>
                    <div class="mr-t-20 mr-b-30 dev-pd-l-13p">
                        <button type="button" class="btn btn-link mr-r-5" data-dismiss="modal">Close</button>
                        <button type="submit" id="addCardButton" class="btn btn-primary pd-l-30 pd-r-30">Add Card</button>
                    </div>
                </div>
                  </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    
    <div id="subscribeModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog custom-modal modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-bind="visible: cancelButtonSubscribe"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title">Subscribe</h4>
                </div>
                <div class="modal-body">
                    <p class="text-center" style="color: blue" data-bind="text: creatingMessage"></p>
                    <p class="text-center" style="color: red" data-bind="text: errorMessage"></p>
                    <p class="text-center" style="color: green;" data-bind="text: successMessage"></p>
                    <p class="text-center">You have already added card please continue to subscribe.</p>
                    <p class="text-center">* You can manage your cards once you login.</p>
                    <div class="mr-t-20 mr-b-30 dev-pd-l-13p">
                        <button type="button" class="btn btn-link mr-r-5" data-dismiss="modal">Close</button>
                        <button type="submit" id="cardAlreadySubscribe" class="btn btn-primary pd-l-30 pd-r-30" data-bind="click: cardAlreadySubscribe">Subscribe</button>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

@endsection

@section('js')
<script type="text/javascript" src="{{asset('web/scripts/knockout-3.4.1.js')}}"></script>
<script src="{{asset('web/scripts/bootstrap-multiselect.js')}}"></script>
<script src="{{asset('web/scripts/bootstrap-select.js')}}"></script>
<script src="{{asset('web/scripts/bootstrap-datepicker.js')}}"></script>
<script src="https://checkout.stripe.com/checkout.js"></script>

<script>

</script>
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
    me.cancelButton = ko.observable(true);
    me.cardNumber = ko.observable();
    me.expiry = ko.observable('');
    me.cvv = ko.observable();
    me.errorMessage = ko.observable('');
    me.trailPeriod = ko.observable();
    me.subscriptionType = ko.observable();
    me.successMessage = ko.observable('');
    me.creatingMessage = ko.observable('');
    me.disableInput = ko.observable(false);
    me.cardExist = ko.observable(false);
    me.cancelButtonSubscribe = ko.observable(true);
    
    me.getSubscriptionList = function () {
        if (me.isLoading()) {
            return false;
        }
        me.isLoading(true);
        $.get('get-subscription-list', {}, function (d) {
            if(typeof d.data != "undefined"){
                if(d.data.length == 0){
                    d.data['trailPeriod'] = 0;
                    d.data['free_trial_period'] = 'with 0 months of free trial'
                }else{
                    d.data['trailPeriod'] = d.data['free_trial_period'];
                    d.data['free_trial_period'] = 'with '+d.data['free_trial_period']+' months free trial';
                }
                d.data['halfYearPrice'] = 60;
                d.data['fullYearPrice'] = 99;
                me.visibleSubcription(true);
                me.subscriptionDetails.push(d.data);
                if(d.customer[0].sources.data.length == 0){
                    me.cardExist(false);
                }else{
                    me.cardExist(true);
                }
            }else{
                me.visibleSubcription(false);
                me.noSubscription(true);
                me.noSubscriptionDetails('We are not provideing service in your area, we will notifiy you whenever we will start our services in your area.');
            }
        }).error(function (xhr, e) {
            me.isLoading(false);
        });
    };
    
    me.addCard = function(d, e){
        me.cardNumber();
        me.expiry();
        me.cvv();
        me.trailPeriod(d.trailPeriod);
        subType = $(e.currentTarget).parent().find('#stype').val();
        me.subscriptionType(subType);
        me.creatingMessage('');
        me.disableInput(false);
        if(me.cardExist() == true){
            $('#cardAlreadySubscribe').removeAttr('disabled');
            $('#subscribeModal').modal('show');
        }else{
            $('#addCardButton').removeAttr('disabled');
            $('#addCardModal').modal('show');
        }
    };
    
    $(".modal").on("hidden.bs.modal", function(){
        me.errorMessage('');
        me.successMessage('');
        me.cardNumber('');
        me.expiry('');
        me.cvv('');
        me.disableInput(false);
    });
    
    me.addCardFunction = function(d, e){
        me.errorMessage('');
        me.successMessage('');
        if(me.expiry() != null && (me.expiry().indexOf('/') >= 0 || me.expiry().indexOf('/') < 0)){
            var expirySplit = me.expiry().split('/');
            if(expirySplit[1] == null || expirySplit[1] == ""){
                me.errorMessage('Invalid expiry date.');
                return false;
            }
            if(expirySplit[0] == null || expirySplit[0] == ""){
                me.errorMessage('Invalid expiry date.');
                return false;
            }
        }
        if(me.cardNumber() != null && me.expiry() != null && me.cvv() != null){
            me.cancelButton(false);
            me.disableInput(true);
            $('#addCardButton').attr('disabled','disabled');
            me.creatingMessage('Adding card please wait...');
            $('#addCardModal').modal({
                backdrop: 'static',
                keyboard: false
            });
            $.post('create-subscription', {cardNumber: me.cardNumber(), expiry: me.expiry(), cvv: me.cvv(), subscriptionType: me.subscriptionType(), trailPeriod: me.trailPeriod(), cardExist: me.cardExist()}, function(d){
                me.creatingMessage('');
                if(d.success == false){
                    me.errorMessage(d.message);
                    me.successMessage('');
                    me.cancelButton(true);
                    $('#addCardButton').removeAttr('disabled');
                    me.disableInput(false);
                }else{
                    me.errorMessage('');
                    me.successMessage(d.message);
                    setTimeout(
                        function ()
                        {
                            location.href = 'jobtemplates';
                        }, 700);
                }
            });
        }else{
            me.errorMessage('Please fill all the details');
            me.creatingMessage('');
            me.cancelButton(true);
            me.disableInput(false);
            $('#addCardModal').modal({
                backdrop: true,
                keyboard: true
            });
        }
    };
    
    me.cardAlreadySubscribe = function(d, e){
        me.errorMessage('');
        me.successMessage('');
        me.cancelButtonSubscribe(false);
        me.creatingMessage('Subscribing please wait...');
        $('#cardAlreadySubscribe').attr('disabled','disabled');
        $('#subscribeModal').modal({
            backdrop: 'static',
            keyboard: false
        });
        $.post('create-subscription', {subscriptionType: me.subscriptionType(), trailPeriod: me.trailPeriod(), cardExist: me.cardExist()}, function(d){
            me.creatingMessage('');
            if(d.success == false){
                me.errorMessage(d.message);
                me.successMessage('');
                me.cancelButtonSubscribe(true);
                $('#cardAlreadySubscribe').removeAttr('disabled');
            }else{
                me.errorMessage('');
                me.successMessage(d.message);
                setTimeout(
                    function ()
                    {
                        location.href = 'jobtemplates';
                    }, 700);
            }
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

