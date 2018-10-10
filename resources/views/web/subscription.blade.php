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
                    <p class="mr-b-25">Quarterly</p>
                    <div class="subcription-price pos-rel">
                        <span class="price-symbol ">$</span>
                        <span class="price" data-bind="text: quarterlyPrice">387</span>
                        <span class="price-duration">per 3 mo.</span>
                        <p>with no free trial</p>
                        <input type="hidden" id="stype" value="1">
                    </div>
                </div>
                <div class="subscription-desc">
                    <p>Unlimited template creation,
                        job posting, searching jobseeker, message & reports</p>
                </div>

                <a id="stripe" data-bind="click: $root.addCard" class="btn btn-primary pd-l-10 pd-r-10 mr-t-20 mr-b-20">Get Started</a>
            </div>
            <div class="subscription-inr-box ">
                <div class="subscription-type">
                    <p class="mr-b-25">Half Yearly</p>
                    <div class="subcription-price pos-rel">
                        <span class="price-symbol ">$</span>
                        <span class="price" data-bind="text: halfYearPrice">594</span>
                        <span class="price-duration">/ 6 mo.</span>
                        <p data-bind="text: free_trial_period">with 1 month free trial</p>
                        <input type="hidden" id="stype" value="2">
                    </div>
                </div>
                <div class="subscription-desc">
                    <p>Unlimited template creation,
                        job posting, searching jobseeker, message & reports</p>
                </div>

                <a id="stripe" data-bind="click: $root.addCard" class="btn btn-primary pd-l-10 pd-r-10 mr-t-20 mr-b-20">Get Started</a>
            </div>

            <div class="subscription-inr-box ">
                <div class="subscription-type">
                    <p class="mr-b-25">Yearly</p>
                    <div class="subcription-price pos-rel">
                        <span class="price-symbol ">$</span>
                        <span class="price" data-bind="text: fullYearPrice">948</span>
                        <span class="price-duration">/ 12 mo.</span>
                        <p data-bind="text: free_trial_period">with 1 month free trial</p>
                        <input type="hidden" id="stype" value="3">
                    </div>
                </div>
                <div class="subscription-desc">
                    <p>Unlimited template creation,
                        job posting, searching jobseeker, message & reports</p>
                </div>

                <a id="stripe" data-bind="click: $root.addCard" class="btn btn-primary pd-l-10 pd-r-10 mr-t-20 mr-b-20">Get Started</a>
            </div>

            <!--/ko-->

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
                            <input type="text" class="form-control" id="card-number" placeholder="Card number" data-bind="value: cardNumber, disable: disableInput, valueUpdate: 'keyup'">
                        </div>
                        <div class="form-group">
                            <label class="sr-only" for="expiry">Expiry</label>
                            <input type="text" class="form-control" id="expiry" placeholder="MM/YYYY" data-bind="value: expiry, valueUpdate: 'keyup', disable: disableInput">
                        </div>
                        <div class="form-group">
                            <label class="sr-only" for="cvv">CVV</label>
                            <input type="number" class="form-control" id="cvv" placeholder="CVV" data-bind="value: cvv, disable: disableInput, valueUpdate: 'keyup'">
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
    </script>
    <script src="{{asset('web/plugins/inputmask/dist/jquery.inputmask.bundle.js')}}"></script>
    <script src="{{asset('web/scripts/subscription.js')}}"></script>
    @endsection

