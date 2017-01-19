@extends('web.layouts.signup')

@section('content')

<div class="container">
    <div class="frm-cred-access-box">

        <div class="row nopadding">
            <div class="col-sm-6 nopadding">
                <div class="denta-logo-box text-center">
                    <img src="{{asset('web/images/dentamatch-logo.png')}}">
                </div>
            </div>
            <div class="col-sm-6 nopadding">
                <div class="frm-inr-credbox bg-white">

                    <form id="forgot-frm" autocomplete="off" data-parsley-validate="" method="POST" action="{{ url('/password/email') }}" >
                        {!! csrf_field() !!}
                        <div class="floating-label">
                            <div class="frm-title mr-b-25">Forgot Password</div>
                            @if (session('status'))
                            <div class="alert alert-success">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                {{ session('status') }}
                            </div>
                            @endif
                            @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            <p >Enter the email address you used when you 
                                joined and we’ll send you instructions to 
                                reset your password.</p>
                            <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">

                                <label class=" control-label" for="email">Email </label>
                                <input type="email" class="form-control " id="email" name="email"
                                       data-parsley-changed="keyup" data-parsley-pattern="/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/" data-parsley-pattern-message="Enter valid Email Id" data-parsley-required-message="Email required" required>
                            </div>
                            
                        </div>

                        <button type="submit" class="btn btn-denta-sm btn-primary btn-block mr-t-20">Send</button>
                        <a href="{{url('login')}}"  class="btn-link btn center-block mr-t-15">Cancel</a>
                    </form>



                </div>
            </div>
        </div>

    </div>

</div>

@endsection