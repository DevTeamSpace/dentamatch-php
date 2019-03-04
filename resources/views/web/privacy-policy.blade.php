@extends('web.layouts.dashboard')

@section('content')
  <div class="container globalpadder">
    <!-- Tab-->
    <div class="row">
      @include('web.layouts.sidebar')
      <div class="col-sm-8 ">
        <div class="resp-tabs-container commonBox profilePadding cboxbottom ">
          <div class="descriptionBox">
            <div class="viewProfileRightCard">
              <div class="detailTitleBlock">
                <div class="frm-title mr-b-10">Privacy Policy</div>
              </div>
              <div class="tabTerms">
                @include('shared.privacy-policy')
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
