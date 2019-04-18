@extends('web.layouts.dashboard')

@section('content')

  <style>
    .pac-container:after {
      content: none !important;
    }
  </style>

  <div class="customContainer center-block containerBottom">
    <div class="profieBoxAbc">
      <h3>Create Your Office Profile</h3>
      @if (count($errors) > 0)
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form data-parsley-validate method="post" id="createProfileForm" class="globalForm" action="javascript:void(0);">
        {{ csrf_field() }}
        <div id="createForm-errors"></div>
        <div class="commonBox cboxbottom">
          <div class="form-group">
            <label>Office Name</label>
            <input type="text" value="{{ old('officeName') }}" onclick="getOfficeName()" id="officeName"
                   name="officeName" class="form-control txtBtnDisable" data-parsley-required
                   data-parsley-required-message="Required" placeholder="Enter Office Name">
          </div>
          <div class="form-group">
            <label>About Our Office</label>
            <textarea class="form-control  txtHeight txtBtnDisable chacterValidtion" name="officeDescription"
                      data-parsley-required data-parsley-required-message="Required" maxlength=500
                      placeholder="Write a sentence or two describing your office for potential candidates.">{{ old('officeDescription') }}</textarea>
          </div>
        </div>
        <div class="clearfix"></div>
        <div class="mainMasterBox">
          <div data-parsley-validate id="officeDetailForm">
            <div id="officeDetail-errors"></div>
            <input type="hidden" name="lat" id="lat">
            <input type="hidden" name="lng" id="lng">
            <input type="hidden" name="full_address" id="full_address">
            <input type="text" style="display:none;" id="postal_code" data-parsley-required
                   data-parsley-required-message="" name="postal_code">

            <div class="commonBox cboxbottom masterBox">
              <div class="form-group">
                <div class="detailTitleBlock">
                  <h5>OFFICE DETAILS</h5>
                </div>
                <label>Office Type</label>
                <div class="slt">
                  <select name="officeType[]" value="" class="ddlCars" multiple="multiple" data-parsley-required
                          data-parsley-required-message="required">
                    @foreach($officeType as $office)
                      <option value="{{$office->id}}">{{$office->officetype_name}}</option>
                    @endforeach
                  </select>
                </div>
                <input type="hidden" value="{{ json_encode($officeType,true) }}" id="officeTypesJson">
              </div>
              <div class="form-group">
                <label>Office Address</label>
                <div id="locationField">
                  <input id="autocomplete" name="officeAddress" value="{{ old('officeAddress') }}" type="text"
                         class="form-control" placeholder="Street Address, City, State, and Zip Code"
                         data-parsley-required data-parsley-required-message="Required">
                  <div id="location-msg"></div>
                </div>
                <input  name="officeAddressSecondLine" value="{{ old('officeAddressSecondLine') }}" type="text"
                        data-parsley-maxlength="300"
                        class="form-control" placeholder="Apartment, suite, unit, building, floor, etc.">
              </div>

              <div class="form-group">
                <label>Office Phone Number</label>
                <input id="phoneNumber" name="phoneNumber" value="{{ old('phoneNumber') }}" type="text"
                       autocomplete="new-password"
                       class="form-control phone-number" placeholder="Office Phone Number" data-parsley-required
                       data-parsley-required-message="Please, Provide a valid Phone number of 10 digits"
                       data-parsley-minlength-message="Please, Provide a valid Phone number of 10 digits"
                       data-parsley-trigger="keyup" data-parsley-minlength="14">

              </div>

              <div class="form-group">
                <label>Working Hours</label>
                <div class="row dayBox EveryDayCheck">
                  <div class="col-sm-4">
                    <p class="ckBox">
                      <input type="checkbox" id="test2" name="everyday" value="1"
                             @if (old('everyday') == "1") checked @endif />
                      <label for="test2" class="ckColor"> Everyday</label>
                    </p>
                  </div>

                  <div class="col-sm-4">
                    <div class='input-group date datetimepicker1 customsel'>
                      <input type='text' value="{{ old('everydayStart') }}" name="everydayStart" class="form-control"
                             disabled/>
                      <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class='input-group date datetimepicker2 customsel'>
                      <input type='text' value="{{ old('everydayEnd') }}" name="everydayEnd" class="form-control "
                             disabled/>
                      <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                    </div>
                  </div>
                </div>

                <div class="allDay">
                  <div class="row dayBox">
                    <div class="col-sm-4">
                      <p class="ckBox">
                        <input type="checkbox" id="mon" name="monday" value="1"
                               @if (old('monday') == "1") checked @endif />
                        <label for="mon" class="ckColor"> Monday</label>
                      </p>
                    </div>


                    <div class="col-sm-4">
                      <div class='input-group date datetimepicker1 customsel'>
                        <input type='text' value="{{ old('mondayStart') }}" name="mondayStart" class="form-control"
                               disabled/>
                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class='input-group date datetimepicker2 customsel'>
                        <input type='text' value="{{ old('mondayEnd') }}" name="mondayEnd" class="form-control "
                               disabled/>
                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                      </div>
                    </div>
                  </div>

                  <div class="row dayBox">
                    <div class="col-sm-4">
                      <p class="ckBox">
                        <input type="checkbox" id="tue" name="tuesday" value="1"
                               @if (old('tuesday') == "1") checked @endif />
                        <label for="tue" class="ckColor"> Tuesday</label>
                      </p>
                    </div>

                    <div class="col-sm-4">
                      <div class='input-group date datetimepicker1 customsel'>
                        <input type='text' value="{{ old('tuesdayStart') }}" name="tuesdayStart" class="form-control"
                               disabled/>
                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class='input-group date datetimepicker2 customsel'>
                        <input type='text' value="{{ old('tuesdayEnd') }}" name="tuesdayEnd" class="form-control "
                               disabled/>
                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                      </div>
                    </div>

                  </div>

                  <div class="row dayBox">
                    <div class="col-sm-4">
                      <p class="ckBox">
                        <input type="checkbox" id="wed" name="wednesday" value="1"
                               @if (old('wednesday') == "1") checked @endif />
                        <label for="wed" class="ckColor"> Wednesday</label>
                      </p>
                    </div>

                    <div class="col-sm-4">
                      <div class='input-group date datetimepicker1 customsel'>
                        <input type='text' value="{{ old('wednesdayStart') }}" name="wednesdayStart"
                               class="form-control" disabled/>
                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class='input-group date datetimepicker2 customsel'>
                        <input type='text' value="{{ old('wednesdayEnd') }}" name="wednesdayEnd" class="form-control "
                               disabled/>
                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                      </div>
                    </div>

                  </div>

                  <div class="row dayBox">
                    <div class="col-sm-4">
                      <p class="ckBox">
                        <input type="checkbox" id="thu" name="thrusday" value="1"
                               @if (old('thrusday') == "1") checked @endif />
                        <label for="thu" class="ckColor"> Thursday</label>
                      </p>
                    </div>

                    <div class="col-sm-4">
                      <div class='input-group date datetimepicker1 customsel'>
                        <input type='text' value="{{ old('thrusdayStart') }}" name="thrusdayStart" class="form-control"
                               disabled/>
                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class='input-group date datetimepicker2 customsel'>
                        <input type='text' value="{{ old('thrusdayEnd') }}" name="thrusdayEnd" class="form-control "
                               disabled/>
                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                      </div>
                    </div>
                  </div>

                  <div class="row dayBox">
                    <div class="col-sm-4">
                      <p class="ckBox">
                        <input type="checkbox" id="fri" name="friday" value="1"
                               @if (old('friday') == "1") checked @endif />
                        <label for="fri" class="ckColor"> Friday</label>
                      </p>
                    </div>

                    <div class="col-sm-4">
                      <div class='input-group date datetimepicker1 customsel'>
                        <input type='text' value="{{ old('fridayStart') }}" name="fridayStart" class="form-control"
                               disabled/>
                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class='input-group date datetimepicker2 customsel'>
                        <input type='text' value="{{ old('fridayEnd') }}" name="fridayEnd" class="form-control "
                               disabled/>
                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                      </div>
                    </div>
                  </div>

                  <div class="row dayBox">
                    <div class="col-sm-4">
                      <p class="ckBox">
                        <input type="checkbox" id="sat" name="saturday" value="1"
                               @if (old('saturday') == "1") checked @endif />
                        <label for="sat" class="ckColor"> Saturday</label>
                      </p>
                    </div>

                    <div class="col-sm-4">
                      <div class='input-group date datetimepicker1 customsel'>
                        <input type='text' value="{{ old('saturdayStart') }}" name="saturdayStart" class="form-control"
                               disabled/>
                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class='input-group date datetimepicker2 customsel'>
                        <input type='text' value="{{ old('saturdayEnd') }}" name="saturdayEnd" class="form-control "
                               disabled/>
                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                      </div>
                    </div>
                  </div>

                  <div class="row dayBox">
                    <div class="col-sm-4">
                      <p class="ckBox">
                        <input type="checkbox" id="sun" name="sunday" value="1"
                               @if (old('sunday') == "1") checked @endif />
                        <label for="sun" class="ckColor"> Sunday</label>
                      </p>
                    </div>

                    <div class="col-sm-4">
                      <div class='input-group date datetimepicker1 customsel'>
                        <input type='text' value="{{ old('sundayStart') }}" name="sundayStart" class="form-control"
                               disabled/>
                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class='input-group date datetimepicker2 customsel'>
                        <input type='text' value="{{ old('sundayEnd') }}" name="sundayEnd" class="form-control "
                               disabled/>
                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="mr-0">Additional Helpful Information <i class="optional">(Optional)</i></label>
                <!--<label class="ex-text">example:where to park, lunch hour, what to wear, etc.</label>-->
                <textarea
                        placeholder="Let future hires know where to park, what to wear, when to expect their lunch hour, etc."
                        name="officeLocation" id="officeLocation" class="form-control txtHeight"
                        data-parsley-required-message="Required" data-parsley-maxlength="500"
                        data-parsley-maxlength-message="Character limit should be 500 characters.">{{ old('officeLocation') }}</textarea>

              </div>
            </div>

            <div class="clearfix"></div>
          </div>
        </div>
        <div class="profieBox"></div>
        <div class="clearfix"></div>
        <div class="addBtn DynamicAddder pull-right pd-t-10 "><span class="icon icon-plus"></span>Add another office
          location (up to 3)
        </div>
        <div class="clearfix"></div>
        <div class="pull-right text-right pd-b-20">
          <div class="pull-right text-right pd-b-15">
            <button id="createProfileButton" type="submit" class="btn btn-primary pd-l-40 pd-r-40">Save</button>
          </div>
      </form>
    </div>


  </div>

  @if(isset($modal))
    <!-- Modal -->
    <div id="onboardView" class="modal " role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">Skip</button>
          </div>
          <div class="modal-body">
            <div class="carousel slide " id="fade-quote-carousel" data-ride="carousel" data-interval="false">
              <!-- Carousel indicators -->
              <ol class="carousel-indicators">
                <li data-target="#fade-quote-carousel" data-slide-to="0" class="active"></li>
                <li data-target="#fade-quote-carousel" data-slide-to="1"></li>
                <li data-target="#fade-quote-carousel" data-slide-to="2"></li>
                <li data-target="#fade-quote-carousel" data-slide-to="3"></li>
                <li data-target="#fade-quote-carousel" data-slide-to="4"></li>
              </ol>
              <!-- Carousel items -->
              <div class="carousel-inner">
                <div class="active item">

                  <div class="onboard-img"><img src="{{asset('web/images/tutorial/icon-01.svg')}}" alt=""></div>
                  <h3 class="onboard-title">Step 1: Let’s make this easy</h3>
                  <blockquote>
                    <p>Welcome to DentaMatch! Follow these steps to build your profile and get started.

                      First up, set your office location(s), hours, and any key information you want temps or job
                      candidates to know about your practice.</p>
                  </blockquote>
                </div>
                <div class="item">

                  <div class="onboard-img"><img src="{{asset('web/images/tutorial/icon-02.svg')}}" alt=""></div>
                  <h3 class="onboard-title">Step 2: Create a Template for each Position in your practice</h3>
                  <blockquote>
                    <p>We’re all about working smarter, so we offer Position Templates you can edit and repost as
                      needed.

                      Just set one up for each position in your office and select the exact skills you want us to match
                      when the time comes.</p>
                  </blockquote>
                </div>
                <div class=" item">

                  <div class="onboard-img"><img src="{{asset('web/images/tutorial/icon-03.svg')}}" alt=""></div>
                  <h3 class="onboard-title">Step 3: Post Jobs</h3>
                  <blockquote>
                    <p>When you need a temp or a new hire, just select from the list of positions you created and post
                      your job.

                      Our matches are based on location, schedule, and skills–so you’ll always get the most qualified
                      candidates available.</p>
                  </blockquote>
                </div>
                <div class=" item">

                  <div class="onboard-img"><img src="{{asset('web/images/tutorial/icon-04.svg')}}" alt=""></div>
                  <h3 class="onboard-title">Step 4: Skip the Middleman</h3>
                  <blockquote>
                    <p>Invite candidates to accept a temp job with a just a click, and save your favorites for recall
                      work.

                      Plus you can clarify details and answer questions directly with in-app messaging.</p>
                  </blockquote>
                </div>
                <div class="item">

                  <div class="onboard-img"><img src="{{asset('web/images/tutorial/icon-05.svg')}}" alt=""></div>
                  <h3 class="onboard-title">Step 5: Know What to Expect</h3>
                  <blockquote>
                    <p>Your Dashboard view keeps key actions and activities at-a-glance.

                      And the Calendar view makes it easy to track open invites and upcoming bookings.</p>
                  </blockquote>
                </div>
              </div>
              <!-- Controls -->
              <a class="left carousel-control" href="#fade-quote-carousel" data-slide="prev">
                <span class="fa  fa-chevron-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
              </a>
              <a class="right carousel-control" href="#fade-quote-carousel" data-slide="next">
                <span class="fa  fa-chevron-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endif
  <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>-->
@section('js')
  <script src="{{asset('web/scripts/dashboard.js')}}"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCsIYaIMo9hd5yEL7pChkVPKPWGX6rFcv8&libraries=places"
          async defer></script>

  <script>
    //$("#officeLocation").Editor();
    //        $("button:submit").click(function(){
    //            $('#officeLocation').text($('#officeLocation').Editor("getText"));
    //            if($('#officeLocation1').length==1){
    //                $('#officeLocation1').text($('#officeLocation1').Editor("getText"));
    //            }
    //            if($('#officeLocation2').length==1){
    //                $('#officeLocation2').text($('#officeLocation2').Editor("getText"));
    //            }
    //        });
  </script>

  <!--&callback=initAutocomplete-->
@endsection
@endsection
