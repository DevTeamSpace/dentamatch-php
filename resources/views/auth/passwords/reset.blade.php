@extends('web.layouts.signup')

@section('content')

<div class="container">
    <div class="frm-cred-access-box">
        <div class="row nopadding flex-block">
            <div class="col-sm-6 nopadding denta-logo-box col">
                <div class=" text-center">
                    <img src="{{asset('web/images/dentamatch-logo.png')}}">
                </div>
            </div>
            <div class="col-sm-6 nopadding col">
                <div class="frm-inr-credbox bg-white">

                    <form id="reset_pswd_frm" autocomplete="off" data-parsley-validate="" method="POST" action="{{ url('/password/reset') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="token" value="{{ $token }}">
                        <input type="hidden" class="form-control" name="email" value="{{ count($email)>0 ? $email[0] : '' }}">
                        <div class="floating-label">
                            <div class="frm-title mr-b-25">Reset Password</div>
                            @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                            @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                            @endif
                            @if ($errors->has('password_confirmation'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                            @endif
                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label class="control-label" for="new-pwd">New Password</label>
                                <input name="password" type="password" class="form-control " id="new-pwd" required data-parsley-trigger="keyup"  data-parsley-length="[6, 14]"  data-parsley-length-message="Password should be 6-14 characters long" data-parsley-required-message="Password required">

                            </div>
                            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                <label class="control-label" for="new-confirmpwd">Confirm Password</label>
                                <input name="password_confirmation" type="password" class="form-control " id="new-confirmpwd" required data-parsley-trigger="keyup"  data-parsley-length="[6, 14]"  data-parsley-length-message="Password should be 6-14 characters long" data-parsley-required-message="Password required" data-parsley-equalto="#new-pwd" data-parsley-equalto-message="Passwords must match">

                            </div>

                        </div>

                        <button type="submit" class="btn btn-denta-sm btn-primary btn-block mr-t-20">Update</button>

                    </form>



                </div>
            </div>
        </div>

    </div>

</div>

@endsection