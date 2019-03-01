@extends('web.layouts.signup')

@section('content')
  <div class="container">
    <div class="frm-cred-access-box bg-white terms-box-outer">
      <div class="terms-box">
        <div class="scrollable_div">
          <div class="frm-title mr-b-40">Terms &amp; Conditions</div>
          <div class="child_scrollable_div pd-0">
            @include('shared.terms-and-conditions')
          </div>
        </div>
      </div>
      <div class="terms-btn text-right">
        <a type="button" href="{{url('logout')}}" class="btn btn-link mr-r-40">Decline</a>

        <a href="tutorial" class="btn btn-primary pd-l-30 pd-r-30">Accept</a>
      </div>
    </div>
  </div>
@endsection