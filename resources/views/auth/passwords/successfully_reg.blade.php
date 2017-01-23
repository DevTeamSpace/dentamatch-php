@extends('web.layouts.signup')

@section('content')
<div class="container">
    <div class="frm-cred-access-box">
        <div class="row nopadding flex-block">
            <div class="col-sm-6 nopadding denta-logo-box col">
                <div class=" text-center ">
                        <img src="{{asset('web/images/dentamatch-logo.png')}}">
                </div>
            </div>
            <div class="col-sm-6 nopadding col">
                <div class="frm-inr-credbox bg-white ">
                    Your account has been activated successfully.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection