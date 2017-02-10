@extends('web.layouts.dashboard')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container globalpadder">
    <!-- Tab-->
    <div class="row" id="subscription">
        @include('web.layouts.sidebar')
        <div class="col-sm-8 ">
            <div class="resp-tabs-container commonBox profilePadding cboxbottom " data-bind="visible: visibleSubcription">
                <div class="descriptionBox">

                    <div class="tabSubscriptionMainContainer">
                        <div class="detailTitleBlock">
                            <div class="frm-title mr-b-10">Subcription Details</div>
                        </div>
                        <div class="tabSubscriptionContainer">
                            <div class="title pull-left pd-b-20">Membership</div>	
                            <a class="pull-right" data-bind="click: $root.unsubscribePlan">Unsubscribe</a>	
                            <div class="clearfix"></div>
                            <div class="table-responsive">
                                <table class="table customSubscriptionTable">
                                    <tbody>
                                        <tr>
                                            <td data-bind="text: subscriptionAmount"></td>
                                            <th>Plan Charged</th>	
                                        </tr>
                                        <tr>
                                            <td>Subscription Plan</td>
                                            <th data-bind="text: subscriptionPlan"></th>	
                                        </tr>
                                        <tr>
                                            <td>Activated On</td>
                                            <th data-bind="text: subscriptionActivation"></th>	
                                        </tr>
                                        <tr>
                                            <td>Auto Renewal Date</td>
                                            <th data-bind="text: subscriptionAutoRenewal"></th>	
                                        </tr>	 	 
                                    </tbody>
                                </table>
                                <p>Your next half yearly charge of <span data-bind="text: subscriptionAmount"></span> will be applied to your primary payment method on <span data-bind="text: subscriptionAutoRenewal"></span>.</p>	
                                <hr>
                                <div class="title pd-b-20 ">Payment Methods</div>
                                <!--ko foreach: cards-->
                                <div class="masterCardBox small-border-radius">
                                    <p class="pull-left"><span data-bind="text: brand"></span> ending in <span data-bind="text: last4"></span> - <span data-bind="text: exp_month"></span>/<span data-bind="text: exp_year"></span></p>
                                    <div class="masterEDOPtion pull-right"><span class="gbllist"><i class="icon icon-edit "></i> Edit</span>
                                        <span class="gbllist" data-bind="click: $root.deleteCard"><i class="icon icon-deleteicon"></i> Delete</span></div>
                                    <div class="clearfix"></div>
                                </div>
                                <br>
                                <!--/ko-->

                                <a href="#" class="pull-right pd-t-20 pd-b-20" data-bind="visible: addCardVisible,click: addCard">Add Payment Method</a>	

                            </div>		
                        </div>
                    </div>
                </div>
            </div>
            <div class="resp-tabs-container commonBox profilePadding cboxbottom " data-bind="visible: noSubscription">
                <p class="text-center" data-bind="text: noSubscriptionDetails"></p>
            </div>
            <div class="resp-tabs-container commonBox profilePadding cboxbottom " data-bind="visible: isLoadingSubscription">
                <p class="text-center" data-bind="text: loadingSubscription"></p>
            </div>
            <div id="addCardModal" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-bind="visible: cancelButton"><span aria-hidden="true">&times;</span></button>
                          <h4 class="modal-title text-center">Add Card</h4>
                        </div>
                        <form id="addCardForm" data-bind="submit: $root.addCardFunction">
                        <div class="modal-body">
                          <p class="text-center">Please provide card details to subscribe.</p>
                          <br>
                          <p class="text-center" style="color: blue" data-bind="text: creatingMessage"></p>
                          <p class="text-center" style="color: red;" data-bind="text: errorMessage"></p>
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
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          <button type="submit" id="addCardButton" class="btn btn-primary">Add Card</button>
                        </div>
                          </form>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            <div id="actionModal" class="modal fade" role="dialog">
                <div class="modal-dialog modal-sm">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" data-bind="visible:cancelButtonDelete">&times;</button>
                            <h4 class="modal-title text-center" data-bind="text:headMessage"></h4>
                        </div>
                        <div class="modal-body">
                            <p class="text-center" data-bind="text:prompt"></p>
                        </div>
                        <div class="modal-footer" data-bind="visible: showModalFooter">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          <button type="submit" id="actionButton" class="btn btn-primary" data-bind="text: actionButtonText"></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script type="text/javascript" src="{{asset('web/scripts/knockout-3.4.1.js')}}"></script>
<script src="https://checkout.stripe.com/checkout.js"></script>

<script>

</script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
var SubscriptionVM = function () {
    var me = this;
    me.isLoading = ko.observable(false);
    me.loadingSubscription = ko.observable('');
    me.isLoadingSubscription = ko.observable(true);
    me.visibleSubcription = ko.observable(false);
    me.subscriptionDetails = ko.observableArray([]);
    me.noSubscription = ko.observable(false);
    me.noSubscriptionDetails = ko.observable('');
    me.cancelButton = ko.observable(true);
    me.cardNumber = ko.observable();
    me.expiry = ko.observable('');
    me.cvv = ko.observable();
    me.errorMessage = ko.observable('');
//    me.trailPeriod = ko.observable();
//    me.subscriptionType = ko.observable();
    me.successMessage = ko.observable('');
    me.creatingMessage = ko.observable('');
    me.disableInput = ko.observable(false);
    me.subscriptionAmount = ko.observable();
    me.subscriptionPlan = ko.observable('');
    me.subscriptionActivation = ko.observable();
    me.subscriptionAutoRenewal = ko.observable();
    me.subscription = ko.observableArray([]);
    me.cards = ko.observableArray([]);
    me.allData = ko.observableArray([]);
    me.prompt = ko.observable('');
    me.cancelButtonDelete = ko.observable(false);
    me.headMessage = ko.observable('');
    me.showModalFooter = ko.observable(true);
    me.actionButtonText = ko.observable('');
    me.addCardVisible = ko.observable(true);
    
    me.getSubscription = function () {
        me.isLoadingSubscription(true);
        me.loadingSubscription('Loading subscription please wait.');
        if (me.isLoading()) {
            return false;
        }
        me.isLoading(true);
        $.get('get-subscription-details', {}, function (d) {
            console.log(d);
            me.isLoadingSubscription(false);
            if(d.success == false || d.data.data.subscriptions.data.length == 0){
                me.noSubscription(true);
                me.visibleSubcription(false);
                me.noSubscriptionDetails('No subscription availed.');
            }else{
                for(i in d.data.data.subscriptions.data){
                    if(d.data.data.subscriptions.data[i].cancel_at_period_end == false){
                        me.noSubscription(false);
                        me.visibleSubcription(true);
                        me.subscriptionAmount("$"+(String)(d.data.data.subscriptions.data[0].plan.amount).slice(0,2));
                        me.subscriptionActivation(moment(Date('Y-m-d',d.data.data.subscriptions.data[0].created)).format('LL'));
                        me.subscriptionAutoRenewal(moment(Date('Y-m-d',d.data.data.subscriptions.data[0].current_period_end)).format('LL'));
                        me.subscription.push(d.data.data.subscriptions.data[0]);
                        if(d.data.data.sources.data.length >= 2){
                            me.addCardVisible(false);
                        }
                        if(d.data.data.subscriptions.data[0].plan.interval_count == 6){
                            me.subscriptionPlan('Half Yearly');
                        }else{
                            me.subscriptionPlan('Yearly');
                        }
                        for(i in d.data.data.sources.data){
                            me.cards.push(d.data.data.sources.data[i]);
                        }
                        me.allData.push(me.subscription()[0], me.cards()[0]);
                        break;
                    }else{
                        me.noSubscription(true);
                        me.visibleSubcription(false);
                        me.noSubscriptionDetails('No subscription availed.');
                    }
                }
            }
        }).error(function (xhr, e) {
            me.isLoading(false);
        });
    };
    
    me.addCard = function(d, e){
        me.cardNumber();
        me.expiry();
        me.cvv();
//        me.trailPeriod(d.trailPeriod);
//        subType = $(e.currentTarget).parent().find('#stype').val();
//        me.subscriptionType(subType);
        me.creatingMessage('');
        me.disableInput(false);
        $('#addCardModal').modal('show');
    };
    
    $(".modal").on("hidden.bs.modal", function(){
        me.errorMessage('');
        me.successMessage('');
        me.cardNumber('');
        me.expiry('');
        me.cvv('');
        me.disableInput(false);
        me.prompt('');
        me.headMessage('');
        me.actionButtonText('');
        me.showModalFooter(false);
        me.cancelButtonDelete(false);
    });
    
    me.addCardFunction = function(d, e){
        me.errorMessage('');
        me.successMessage('');
        if(me.expiry() != null && me.expiry().indexOf('/') >= 0){
            var expirySplit = me.expiry().split('/');
            if(expirySplit[1] == null || expirySplit[1] == ""){
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
            $.post('add-card', {cardNumber: me.cardNumber(), expiry: me.expiry(), cvv: me.cvv()}, function(d){
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
                            location.reload();
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
    
    me.deleteCard = function(d, e){
        if(me.cards().length <= 1){
            me.prompt('Cannot delete card, you should have atleast one card added to continue the subscription.');
            me.headMessage('Card');
            me.showModalFooter(false);
            me.cancelButtonDelete(true);
            $('#actionModal').modal('show');
            return false;
        }else{
            me.prompt('Do you want to delete card.');
            me.headMessage('Delete Card');
            me.actionButtonText('Delete');
            me.showModalFooter(true);
            me.cancelButtonDelete(true);
            $('#actionModal').modal('show');
            $('#actionButton').on('click', function(){
                me.prompt('Deleting card please wait.');
                me.headMessage('Delete Card');
                me.cancelButtonDelete(false);
                me.showModalFooter(false);
                $.post('delete-card', {cardId: d.id}, function(d){
                    if(d.success == false){
                        me.prompt('Cannot delete card, please contact admin.');
                        me.headMessage('Delete Card');
                        me.cancelButtonDelete(true);
                        me.showModalFooter(true);
                    }else{
                        me.prompt('Card deleted successfully.');
                        setTimeout(
                            function ()
                            {
                                location.reload();
                            }, 700);
                    }
                });
            });
        }
    };
    
    me.unsubscribePlan = function(d, e){
        me.prompt('Do you want to unsubscribe ?');
        me.headMessage('Unsubscribe');
        me.showModalFooter(true);
        me.cancelButtonDelete(true);
        me.actionButtonText('Unsubscribe');
        $('#actionModal').modal('show');
        $('#actionButton').on('click', function(){
            me.prompt('Unsubscribing please wait.');
            me.cancelButtonDelete(false);
            me.showModalFooter(false);
            subscriptionId = d.subscription()[0].id;
            $.post('unsubscribe', {subscriptionId: subscriptionId}, function(d){
                if(d.success == true){
                    me.prompt('Unsubscribed successfully.');
                }else{
                    me.prompt(d.message);
                }
                setTimeout( function(){
                    location.reload();
                }, 700);
            });
        });
    };
    
    me._init = function () {
        $('body').find('#ChildVerticalTab_1').find('li').removeClass('resp-tab-active');
        $('body').find('#ChildVerticalTab_1').find('li:nth-child(5)').addClass('resp-tab-active')
        me.getSubscription();
    };
    me._init();
};
var ssObj = new SubscriptionVM();
ko.applyBindings(ssObj, $('#subscription')[0])
</script>
@endsection
