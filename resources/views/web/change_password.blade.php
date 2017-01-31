@extends('web.layouts.dashboard')

@section('content')
<div class="container globalpadder">
    <!-- Tab-->
    <div class="row">
        <div class="col-sm-4">
            <div id="ChildVerticalTab_1"  class="cboxbottom">
                <ul class="resp-tabs-list ver_1 small-border-radius  decoration">
                    <li class="resp-tab-active"><a href ="#"><span class="icon icon-account-circle  "></span>Profile</a></li>
                    <li><a href ="#"><span class="icon icon-drive-document "></span>Reports</a></li>
                    <li><a href ="#"><span class="icon icon-lock "></span>Change Password</a></li>
                    <li><a href ="#"><span class="icon icon-drive-form "></span>Terms & Conditions</a></li>
                    <li>
                        <a href ="#">
                            <span class="icon icon-text-document-black-interface-symbol "></span>Subscription Details</a>
                    </li>
                    <li><a href ="#"><span class="icon icon-logout-web-button "></span>Logout</a></li>
                </ul>
            </div>
        </div>
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
                                <div class="form-group profieBox">
                                    <label for="oldpassword">Old Password</label>
                                    <input type="password" name="oldPassword" class="form-control" id="oldpassword" data-parsley-required-message="Old password required" required >
                                </div>
                                <div class="form-group profieBox">
                                    <label for="password">New Password</label>
                                    <input type="password" name="password" class="form-control" id="password" data-parsley-required-message="New password required" required>
                                </div>
                                <div class="form-group profieBox">
                                    <label for="password_confirmation">Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" data-parsley-required-message="Confirm password required" required>
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