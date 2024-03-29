@extends('web.layouts.dashboard')

@section('content')
<div class="container globalpadder">
    <!-- Tab-->
    <div class="row">
        @include('web.layouts.sidebar')
        <div class="col-sm-8 ">
            <div class="addReplica">
                <div class="resp-tabs-container commonBox profilePadding cboxbottom ">
                    <div class="descriptionBox">
                        <form  autocomplete="off" data-parsley-validate method="post" action="{{url('change-password')}}">
                            {{ csrf_field() }}
                            <div class="viewProfileRightCard">
                                <div class="detailTitleBlock">
                                    <div class="frm-title mr-b-25">Change Password</div>
                                </div>
                                @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                                 @if(Session::has('success'))
                                <h6 class="alert alert-success">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    {{ Session::get('success') }}</h6>
                                @endif
                                <div class="form-group profieBox">
                                    <label for="oldpassword">Old Password</label>
                                    <input type="password" name="oldPassword" class="form-control" id="oldpassword" data-parsley-required-message="Old password required" required data-parsley-trigger="keyup" data-parsley-length="[6, 14]"  data-parsley-length-message="Password should be 6-14 characters long" >
                                </div>
                                <div class="form-group profieBox">
                                    <label for="password">New Password</label>
                                    <input type="password" name="password" class="form-control" id="password" data-parsley-required-message="New password required" required data-parsley-trigger="keyup"  data-parsley-length="[6, 14]"  data-parsley-length-message="Password should be 6-14 characters long" >
                                </div>
                                <div class="form-group profieBox">
                                    <label for="password_confirmation">Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" data-parsley-required-message="Confirm password required" required data-parsley-trigger="keyup"  data-parsley-length="[6, 14]"  data-parsley-length-message="Password should be 6-14 characters long" data-parsley-equalto="#password" data-parsley-equalto-message="Passwords must match">
                                </div>
                                <div class="pull-right text-right"> <button type="submit" class="btn btn-primary pd-l-40 pd-r-40">Update</button> </div>
                                <br><br>					
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
