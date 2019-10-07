@extends('web.layouts.signup')

@section('content')

  <meta name="csrf-token" content="{{ csrf_token() }}">
  <div class="container" id="subscription">
    <div class="frm-cred-access-box subscription-box" style="display: none" data-bind="visible: subscriptionAvailable() || noSubscription()">
      <h4 class="frm-title">Our Subscription Plans</h4>
      <p>Unlock unlimited template creation, job postings, candidate searches, messaging and reports.</p>
      <form class="form-inline mr-t-20" id="promo-code-form" data-bind="submit: checkPromoCode">
        <div class="form-group mx-sm-3 mb-2 mr-r-10">
          <label for="promo-code-input" class="sr-only">Promo Code</label>
          <input type="text" class="form-control" id="promo-code-input" name="promo-code"
                 data-bind="value: promoCode, disable: codeSubmitting, valueUpdate: 'input'"
                 placeholder="Enter Promo Code">
        </div>
        <button type="submit" class="btn btn-primary" data-bind="disable: codeSubmitting() || !promoCode(), hidden: selectedSubscription()">Apply</button>
        <button type="button" class="btn btn-link" data-bind="visible: selectedSubscription(), click: clearCode">Remove</button>
        <div class="text-danger mr-t-5" style="position: absolute" data-bind="text: codeMessage"></div>
      </form>

      <div class="subs-holder text-center ">
        <!--ko foreach: subscriptionDetails-->
        <div class="subscription-inr-box" data-bind="class: $root.monthlyClass()">
          <div class="subscription-type">
            <p class="mr-b-10">Monthly</p>
            <div class="subcription-price pos-rel">
              <span class="price-symbol ">$</span>
              <span class="price" data-bind="text: monthlyPrice"></span>
              <p class="mr-t-15" data-bind="text: $root.couponText, visible: $root.selectedSubscription() == 'Monthly'"></p>
              <input type="hidden" id="stype" value="1">
            </div>
          </div>
          <a id="stripe" data-bind="click: $root.showAddCardPopup, hidden: $root.selectedSubscription() && $root.selectedSubscription() != 'Monthly'"
             class="btn btn-primary pd-l-10 pd-r-10 mr-t-10 mr-b-20">Next
            Step</a>
        </div>
        <div class="subscription-inr-box" data-bind="class: $root.semiAnnualClass()">
          <div class="subscription-type">
            <p class="mr-b-10">Semi-Annual</p>
            <div class="subcription-price pos-rel">
              <span class="price-symbol ">$</span>
              <span class="price" data-bind="text: halfYearPrice"></span>/month
              <p class="mr-t-15" data-bind="text: $root.couponText, visible: $root.selectedSubscription() == 'Semi-Annual'"></p>
              <input type="hidden" id="stype" value="2">
            </div>
          </div>
          <a id="stripe" data-bind="click: $root.showAddCardPopup, hidden: $root.selectedSubscription() && $root.selectedSubscription() != 'Semi-Annual'"
             class="btn btn-primary pd-l-10 pd-r-10 mr-t-10 mr-b-20">Next Step</a>
        </div>

        <div class="subscription-inr-box" data-bind="class: $root.annualClass()">
          <div class="subscription-type">
            <p class="mr-b-10">Annual</p>
            <div class="subcription-price pos-rel">
              <span class="price-symbol ">$</span>
              <span class="price" data-bind="text: fullYearPrice"></span>/month
              <p class="text-uppercase mr-t-15">Best Value</p>
              <p class="mr-t-15" data-bind="text: $root.couponText, visible: $root.selectedSubscription() == 'Annual'"></p>
              <input type="hidden" id="stype" value="3">
            </div>
          </div>
          <a id="stripe" data-bind="click: $root.showAddCardPopup, hidden: $root.selectedSubscription() && $root.selectedSubscription() != 'Annual'"
             class="btn btn-primary pd-l-10 pd-r-10 mr-t-10 mr-b-20">Next Step</a>
        </div>

        <!--/ko-->

      </div>
      <div class="frm-cred-access-box subscription-box" style="display: none" data-bind="visible: noSubscription">
        <h3 class="no-subscription-heading text-center">We are not providing service in your area, we will notify you
          whenever we will start our services in your area.</h3>
        <p class="text-center mr-t-35">Or you can update <a href="/edit-profile">your offices</a> information</p>
      </div>

      <div class="subscription-terms mr-t-20">
        <div class="mr-b-10"><a href="/edit-profile"><span class="fa fa-arrow-left"></span> Back to profile</a></div>
        <p><span>NO</span> Daily Temp Fees</p>
        <p><span>NO</span> Finders Fees</p>
        <p>Use DentaMatch as much as you need for a low subscription price</p>
      </div>

    </div>
    <div id="addCardModal" class="modal fade" tabindex="-1" role="dialog">
      <div class="modal-dialog custom-modal modal-sm" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                    data-bind="hidden: disableInput"><span aria-hidden="true">&times;</span></button>
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
                <input type="text" class="form-control" id="card-number" placeholder="Card number"
                       data-bind="value: cardNumber, disable: disableInput, valueUpdate: 'keyup'">
              </div>
              <div class="form-group">
                <label class="sr-only" for="expiry">Expiry</label>
                <input type="text" class="form-control" id="expiry" placeholder="MM/YYYY"
                       data-bind="value: expiry, valueUpdate: 'keyup', disable: disableInput">
              </div>
              <div class="form-group">
                <label class="sr-only" for="cvv">CVV</label>
                <input type="number" class="form-control" id="cvv" placeholder="CVV"
                       data-bind="value: cvv, disable: disableInput, valueUpdate: 'keyup'">
              </div>
              <div class="mr-t-20 mr-b-30 dev-pd-l-13p">
                <button type="button" class="btn btn-link mr-r-5" data-dismiss="modal"
                        data-bind="disable: disableInput">Close
                </button>
                <button type="submit" class="btn btn-primary pd-l-30 pd-r-30" data-bind="disable: disableInput">Add
                  Card
                </button>
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
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                    data-bind="hidden: disableInput"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Subscribe</h4>
          </div>
          <div class="modal-body">
            <p class="text-center" style="color: blue" data-bind="text: creatingMessage"></p>
            <p class="text-center" style="color: red" data-bind="text: errorMessage"></p>
            <p class="text-center" style="color: green;" data-bind="text: successMessage"></p>
            <p class="text-center" data-bind="hidden: noPayment">You have already added card please continue to subscribe.</p>
            <p class="text-center" data-bind="hidden: noPayment">* You can manage your cards once you login.</p>
            <p class="text-center" data-bind="visible: noPayment">You are about to subscribe</p>
            <div class="mr-t-20 mr-b-30 dev-pd-l-13p">
              <button type="button" class="btn btn-link mr-r-5" data-dismiss="modal" data-bind="disable: disableInput">
                Close
              </button>
              <button type="submit" class="btn btn-primary pd-l-30 pd-r-30"
                      data-bind="click: subscribeFunction, disable: disableInput">Subscribe
              </button>
            </div>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div id="successModal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog custom-modal modal-sm" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Thank you</h4>
          </div>
          <div class="modal-body">
            <p class="text-center js-long">Thank you for your subscription! <br>
              You have successfully signed up for DentaMatch. <br>
              <span data-bind="visible: noPayment" ><br>Your free trial begins today.</span>
            </p>

            <p class="text-center js-month">Thank you for your subscription! <br>
              <span data-bind="hidden: noPayment">Enjoy all the benefits of DentaMatch for one month! <br><br></span>
              <span data-bind="visible: noPayment" ><br>Your free trial begins today.</span>
              <span data-bind="hidden: subscriptionIsCancelled() || noPayment()">Your monthly subscription will automatically renew after the first month OR you can <br>
                <button type="button" class="btn-link"
                        data-bind="click: cancelSubscriptionFunction, disable: disableInput">click here</button> for a one-time only charge.
              </span>
            </p>

            <p class="text-center" style="color: blue" data-bind="text: creatingMessage"></p>

            <div class="mr-t-20 mr-b-30 dev-pd-l-13p text-center">
              <a class="btn btn-primary" href="jobtemplates" data-bind="disable: disableInput">Go to Job Templates</a>
              {{--<button type="submit" class="btn btn-primary pd-l-30 pd-r-30"--}}
              {{--data-bind="click: subscribeFunction, disable: disableInput">Subscribe--}}
              {{--</button>--}}
            </div>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    @endsection

    @section('js')
      <script type="text/javascript" src="{{asset('web/scripts/knockout-3.5.0.js')}}"></script>
      <script src="{{asset('web/scripts/bootstrap-multiselect.js')}}"></script>
      <script src="{{asset('web/scripts/bootstrap-select.js')}}"></script>
      <script src="{{asset('web/scripts/bootstrap-datepicker.js')}}"></script>

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

