@extends('web.layouts.page')

@section('css')
  <link rel="stylesheet" href="{{asset('web/css/optionDropDown.css')}}">
@endsection

@section('content')
  <main class="page-container page--candidate">
    <section class="page-content">
      <h1 class="page-title">Candidate Signup</h1>

      @if (count($errors) > 0)
        <div class="alert {{ Session::get('alert-class', 'alert-info') }}">
          <ul>
            @foreach($errors->all() as $error)
              <li>{{$error}}</li>
            @endforeach
          </ul>
        </div>
      @endif
      @if(Session::has('message'))
        <p class="alert {{ Session::get('alert-class', 'alert-info') }}">
          {{ Session::get('message') }}
          <span class="close" data-dismiss="alert">&times;</span>
        </p>
      @endif
      
      <form class="page-form" data-parsley-validate
            method="post" action="{{ url('jobseeker/storeSignup') }}">
        {!! csrf_field() !!}
        <input type="hidden" name="jobtitles" id="jsonVal" value="{{ json_encode($jobTitleData) }}">

        <div class="page-form__fields">
          <div class="d-form-group">
            <label for="first-name">First Name</label>
            <input type="text" placeholder="First Name" name="firstName"
                   value="{{ app('request')->input('firstName') }}" required
                   id="first-name" class="d-form-control">
          </div>
          <div class="d-form-group">
            <label for="last-name">Last Name</label>
            <input type="text" placeholder="Last Name" name="lastName"
                   value="{{ app('request')->input('lastName') }}" required
                   id="last-name" class="d-form-control">
          </div>

          <div class="d-form-group">
            <label for="email">Email</label>
            <input type="email" placeholder="Email" name="email"
                   value="{{ app('request')->input('email') }}" required
                   id="email" class="d-form-control">
          </div>

          <div class="d-form-group">
            <label for="email">Job Title</label>
            <div class="slt custom-select">
              <select id="jobTitleId" name="jobTitleId" class="selectpicker mr-b-5" data-parsley-required
                      data-parsley-required-message="Job title is required" required>
                <option value="">Select job title</option>
                @foreach ($jobTitleData as $key=>$jobTitle)
                  @if($key==0)
                    <option data-divider="true"></option>
                  @endif
                  @if($jobTitle['id'] == app('request')->input('jobTitleId'))
                    <option selected='true' value="{{ $jobTitle['id'] }}">{{ $jobTitle['jobtitle_name'] }}</option>
                  @else
                    <option value="{{ $jobTitle['id'] }}">{{ $jobTitle['jobtitle_name'] }}</option>
                  @endif
                  <option data-divider="true"></option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="d-form-group licenceGroup licenseId">
            <label>License</label>
            <input placeholder="License No" value="{{ app('request')->input('license') }}"
                   name="license" type="text" class="d-form-control">
          </div>

          <div class="d-form-group licenceGroup licenseId">
            <label>License State</label>
            <input placeholder="State" value="{{ app('request')->input('state') }}" name="state"
                   type="text" class="d-form-control">
          </div>

          <div class="d-form-group">
            <label>Preferred Job Locations</label>
            <div class="slt custom-select">
              <select data-parsley-required data-parsley-required-message="Select preferred location"
                      name="preferredJobLocationId" id="preferredJobLocationId"
                      class="selectpicker mr-b-5">
                <option value="">Select preferred job locations</option>
                @foreach ($preferredLocationId as $key=>$prefLocation)
                  @if($key==0)
                    <option data-divider="true"></option>
                  @endif
                  @if($prefLocation['id'] == app('request')->input('preferredJobLocationId'))
                    <option selected='true'
                            value="{{ $prefLocation['id'] }}">{{ $prefLocation['preferred_location_name'] }}</option>
                  @else
                    <option value="{{ $prefLocation['id'] }}">{{ $prefLocation['preferred_location_name'] }}</option>
                  @endif
                  <option data-divider="true"></option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="d-form-group">
            <label>About Me</label>
            <textarea data-parsley-required data-parsley-required-message="About me is required"
                      class="d-form-control"
                      name="aboutMe">{{ app('request')->input('aboutMe') }}</textarea>
          </div>

          <div class="d-form-group">
            <input type="checkbox" name="privacy" id="checkbox"
                   style="-webkit-appearance:checkbox; -moz-appearance:checkbox; margin-right:5px;" required=""
                   value=""><strong>I agree to the <a href="{{ url('/terms-and-conditions') }}" target="_blank">Terms and Conditions</a>
              and <a href="{{ url('/privacy-policy') }}" target="_blank">Privacy policy</a></strong>
          </div>
        </div>

        <div class="page-form__submit-btn">
          <button id="Save" class="d-btn btn--solid btn--mini" type="submit">Save</button>
        </div>

      </form>
    </section>

    <section class="page-picture page-picture--candidate">
      <a href="/login" class="d-btn btn--blank">I'm a Dental Practice</a>
    </section>

  </main>
@endsection

@section('js')
  <script src="{{asset('web/scripts/optionDropDown.js')}}"></script>
  <script src="{{asset('web/scripts/custom.js')}}"></script>
  <script>
    $('form').submit(function (e) {
      var form = $(this);
      form.parsley().validate();
      if (form.parsley().isValid()) {
        $('#Save').attr('disabled', true);
      }
    });

    $('#jobTitleId > option').each(function (index) {
      $(this).attr("customIndex", index);
    });

    $('#jobTitleId').change(function () {
      var listID, jsonValue;
      listID = $(this).val();
      jsonValue = JSON.parse($(jsonVal).val());
      for (var i = 0; i < jsonValue.length; i++) {
        if (jsonValue[i].id == listID) {
          if (jsonValue[i].is_license_required == 1) {
            $('.licenceGroup').removeClass("licenseId");
          }

          else {
            $('.licenceGroup').addClass("licenseId");
          }
          break;
        }
      }
    });
    $('#jobTitleId').change();
  </script>
@endsection