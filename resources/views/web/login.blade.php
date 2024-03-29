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
                    <div class="f14w300 mr-b-20">Please sign up or login to access our dental office portal.</div>
                    <ul class="nav nav-pills">
                        <li><a data-toggle="pill" href="#signup">Sign up</a></li>
                        <li class="active">
                            <a data-toggle="pill" href="#login">Login</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="signup" class="tab-pane fade">
                            <form id="signup-frm" method="post" action="{{ url('signup') }}" name="signupform" autocomplete="off" data-parsley-validate="" >
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="floating-label">
                                    <div class="form-group ">
                                        <label class=" control-label" for="signup-email">Email </label>
                                        <input type="email" name="email" class="form-control" id="signup-email"  
                                        data-parsley-changed="keyup" data-parsley-pattern="/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/" data-parsley-pattern-message="Enter valid Email Id" data-parsley-required-message="Email required" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="signup-pwd">Password</label>
                                        <input type="password" name="password" class="form-control " id="signup-pwd" required data-parsley-trigger="keyup"  data-parsley-length="[6, 14]"  data-parsley-length-message="Password should be 6-14 characters long" data-parsley-required-message="Password required">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="signup-confirmpwd">Confirm Password</label>
                                        <input type="password" name="confirmPassword" class="form-control " id="signup-confirmpwd" required data-parsley-trigger="keyup"  data-parsley-length="[6, 14]"  data-parsley-length-message="Password should be 6-14 characters long" data-parsley-required-message="Password required" data-parsley-equalto="#signup-pwd" data-parsley-equalto-message="Passwords must match">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-denta-sm btn-primary  btn-block mr-t-20">Create Account</button>
                            </form>
                            <div class="f14w300 mr-t-20">Download our <a href="#">iOS</a> or <a href="#">Android</a> app if you’re dental professional looking for temp, full-time, or part-time work.</div>
                        </div>
                        <div id="login" class="tab-pane fade in active">
                            @if(Session::has('message'))
                                <h6 class="alert alert-danger">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    {{ Session::get('message') }}</h6>
                            @endif
                            @if(Session::has('success'))
                                <h6 class="alert alert-success">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    {{ Session::get('success') }}</h6>
                            @endif
                            <form id="signin-frm" method="post" action="{{ url('login') }}" name="loginform" autocomplete="off" data-parsley-validate="" >
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="floating-label">
                                    <div class="form-group ">
                                        <label class=" control-label" for="email">Email </label>
                                        <input type="email" name="email" class="form-control " id="email" 
                                        data-parsley-changed="keyup" data-parsley-pattern="/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/" data-parsley-pattern-message="Enter valid Email Id" data-parsley-required-message="Email required" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="pwd">Password</label>
                                        <input type="password" name="password" class="form-control " id="pwd" required data-parsley-trigger="keyup" data-parsley-length="[6, 14]"  data-parsley-length-message="Password should be 6-14 characters long"  data-parsley-required-message="password required">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-denta-sm btn-primary btn-block mr-t-20">Login</button>
                                    <a href="{{url('password/reset')}}"  class="btn-link btn center-block mr-t-15">Forgot Password?</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection