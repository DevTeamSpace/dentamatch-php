@extends('web.layouts.signup')

@section('content')

<div class="container">
    <div class="frm-cred-access-box subscription-box">
        <h4 class="frm-title">Our Subscription Plan</h4>
        <p>Unlock unlimited access to jobs post and finding suitable jobseekers.</p>
        <div class="subs-holder text-center ">

            <div class="subscription-inr-box ">
                <div class="subscription-type">
                    <p class="mr-b-25">Half Yearly</p>
                    <div class="subcription-price pos-rel">
                        <span class="price-symbol ">$</span>
                        <span class="price">60</span>
                        <span class="price-duration">/ 6 mo.</span>
                        <p>with 1 months free trial</p>
                    </div>
                </div>
                <div class="subscription-desc">
                    <p>Unlimited template creation,
                        job posting, searching jobseeker, message & reports</p>
                </div>
                <a href="#" class="btn btn-primary pd-l-10 pd-r-10 mr-t-20 mr-b-20">Get Started</a>
            </div>


            <div class="subscription-inr-box ">
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
                <a href="#" class="btn btn-primary pd-l-10 pd-r-10 mr-t-20 mr-b-20">Get Started</a>
            </div>




        </div>
    </div>

</div>

@endsection
