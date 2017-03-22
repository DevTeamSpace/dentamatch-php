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
                            <div class="title pull-left pd-b-20"><b>Membership</b></div>	
                            <a class="pull-right" href="#" data-bind="visible: unsubscribeButton,click: $root.unsubscribePlan">Unsubscribe</a>
                            <a class="pull-right" href="#" data-bind="visible: resubscribeButton,click: $root.subscribeAgain">Subscribe Again</a>
                            <div class="clearfix"></div>
                            <div class="table-responsive">
                                <!--ko foreach: subscription-->
                                <table class="table customSubscriptionTable">
                                    <tbody>
                                        <tr>
                                            <td>Plan Charged</td>
                                            <th data-bind="text: subscriptionAmount"></th>	
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
                                <p>Your next <span data-bind="text: subscriptionPlan"></span> charge of <span data-bind="text: subscriptionAmount"></span> will be applied to your primary payment method on <span data-bind="text: subscriptionAutoRenewal"></span>( i.e. <span data-bind="text: leftDays"></span> Days).</p>
                                <!--/ko-->
                                <div class="text-right" data-bind="visible: switchVisible">
                                    <button type="submit" class="btn btn-primary pd-l-30 pd-r-30 mr-t-10" data-bind="text: switchToText, click: $root.switchTo"></button>
                                </div>
                                <hr>
                                <div class="title pd-b-20 "><b>Payment Methods</b></div>
                                <!--ko foreach: cards-->
                                <div class="masterCardBox small-border-radius dev_card_box">
                                    <p class="pull-left"><span data-bind="text: brand"></span> ending in <span data-bind="text: last4"></span> - <span data-bind="text: exp_month"></span>/<span data-bind="text: exp_year"></span></p>
                                    <div class="masterEDOPtion pull-right"><span class="gbllist dev_edit_button" data-bind="click: $root.editCard"><i class="icon icon-edit"></i> Edit</span>
                                        <span class="gbllist" data-bind="click: $root.deleteCard"><i class="icon icon-deleteicon"></i> Delete</span></div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <!--/ko-->
                                    <a href="#" class="pull-right pd-t-10 pd-b-20" data-bind="visible: addCardVisible,click: addCard"><b>Add Payment Method</b></a>	

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
                    <div class="modal-dialog custom-modal modal-sm" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-bind="visible: cancelButton"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Add Card</h4>
                            </div>
                            <form id="addCardForm" data-bind="submit: $root.addCardFunction">
                                <div class="modal-body">
                                  <p class="text-center">Please provide card details.</p>
                                  <br>
                                  <p class="text-center" style="color: blue" data-bind="text: creatingMessage"></p>
                                  <p class="text-center" style="color: red;" data-bind="text: errorMessage"></p>
                                  <p class="text-center" style="color: green;" data-bind="text: successMessage"></p>
                                  <br>
                                  <div class="form-group">
                                    <label class="sr-only" for="card-number">Card number</label>
                                    <input type="text" class="form-control" id="card-number" placeholder="Card number" data-bind="value: cardNumber, disable: disableInput">
                                </div>
                                <div class="form-group">
                                    <label class="sr-only" for="expiry">Expiry</label>
                                    <input type="text" class="form-control" id="expiry" placeholder="MM/YY" data-bind="value: expiry, valueUpdate: 'keyup', disable: disableInput">
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
            <div id="editCardModal" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog custom-modal modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-bind="visible: cancelButtonEdit"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Update</h4>
                        </div>
                        <form id="addCardForm" data-bind="submit: $root.editCardFunction">
                            <div class="modal-body">
                              <p class="text-center">Please provide expiry details to update card.</p>
                              <br>
                              <p class="text-center" style="color: blue" data-bind="text: creatingMessage"></p>
                              <p class="text-center" style="color: red;" data-bind="text: errorMessage"></p>
                              <p class="text-center" style="color: green;" data-bind="text: successMessage"></p>
                              <br>
<!--                            <div class="form-group">
                                <label class="sr-only" for="card-number">Card number</label>
                                <input type="number" class="form-control" placeholder="Card number" data-bind="value: editCardNumber" disabled="disable">
                            </div>-->
                            <div class="form-group">
                                <label class="sr-only" for="expiry">Expiry</label>
                                <input type="text" class="form-control" id="editExpiry" placeholder="MM/YY" data-bind="value: editExpiry">
                            </div>
<!--                            <div class="form-group">
                                <label class="sr-only" for="cvv">CVV</label>
                                <input type="number" class="form-control" placeholder="CVV" data-bind="value: editCvv">
                            </div>-->
                            <div class="mr-t-20 mr-b-30 dev-pd-l-13p">
                                <button type="button" class="btn btn-link mr-r-5" data-dismiss="modal">Close</button>
                                <button type="submit" id="editCardButton" class="btn btn-primary pd-l-30 pd-r-30">Update Card</button>
                            </div>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <div id="actionModal" class="modal fade" role="dialog">
            <div class="modal-dialog custom-modal modal-sm">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" data-bind="visible:cancelButtonDelete">&times;</button>
                        <h4 class="modal-title" data-bind="text:headMessage"></h4>
                    </div>
                    <div class="modal-body">
                        <p class="text-center" data-bind="text:prompt"></p>
                        <div class="mr-t-20 mr-b-30 dev-pd-l-13p" data-bind="visible: showModalFooter">
                            <button type="button" class="btn btn-link mr-r-5" data-dismiss="modal">Close</button>
                            <button type="submit" id="actionButton" class="btn btn-primary pd-l-30 pd-r-30" data-bind="text: actionButtonText"></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
@section('js')
<script src="https://checkout.stripe.com/checkout.js"></script>

<script>

</script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script src="{{asset('web/scripts/setting-subscription.js')}}"></script>
@endsection
