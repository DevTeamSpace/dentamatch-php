@extends('web.layouts.dashboard')

@section('content')

<style>
    .pac-container:after{
        content:none !important;
    }
</style>

<div class="customContainer center-block containerBottom">
    <div class="profieBoxAbc">
        <h3>Create Profile</h3>
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
                    <label >Dental Office Name</label>
                    <input type="text" value="{{ old('officeName') }}" onclick="getOfficeName()" id="officeName" name="officeName" class="form-control txtBtnDisable"  data-parsley-required data-parsley-required-message="Required" placeholder="Enter Dental Office Name">
                </div>
                <div class="form-group">
                    <label  >Dental Office Description</label>
                    <textarea class="form-control  txtHeight txtBtnDisable chacterValidtion"  name="officeDescription"  data-parsley-required data-parsley-required-message="Required" maxlength=500 >{{ old('officeDescription') }}</textarea>
                </div>
            </div>	
            <div class="clearfix"></div>
            <div class="mainMasterBox">
                <div data-parsley-validate id="officeDetailForm">
                    <div id="officeDetail-errors"></div>
                    <input type="hidden" name="lat" id="lat">
                    <input type="hidden" name="lng" id="lng">
                    <input type="hidden" name="full_address" id="full_address">
                    <input type="text" style="display:none;"  id="postal_code" data-parsley-required data-parsley-required-message=""  name="postal_code">

                    <div class="commonBox cboxbottom masterBox">
                        <div class="form-group">
                            <div class="detailTitleBlock">
                                <h5>OFFICE DETAILS</h5>
                            </div>
                            <label >Dental Office Type</label>
                            <div class="slt">
                                <select  name="officeType[]" value="" class="ddlCars" multiple="multiple" data-parsley-required data-parsley-required-message="required">
                                    @foreach($officeType as $office)
                                    <option value="{{$office->id}}" >{{$office->officetype_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <input type="hidden" value="{{ json_encode($officeType,true) }}" id="officeTypesJson">
                        </div>
                        <div class="form-group">
                            <label>Dental Office Address</label>
                            <div id="locationField">
                                <input  id="autocomplete" name="officeAddress" value="{{ old('officeAddress') }}" type="text" class="form-control"  placeholder="Office name, Street, City, Zip Code and Country" data-parsley-required data-parsley-required-message="Required">
                                <div id="location-msg"></div>
                            </div>
                        </div>


                        <div class="form-group">
                            <label>Phone Number</label>
                            <input id="phoneNumber" name="phoneNumber" value="{{ old('phoneNumber') }}" type="text" class="form-control phone-number" data-parsley-required data-parsley-required-message="Please, Provide a valid Phone number of 10 digits" data-parsley-minlength-message="Please, Provide a valid Phone number of 10 digits"   data-parsley-trigger="keyup" data-parsley-minlength="14"  >

                        </div>

                        <div class="form-group">
                            <label >Working Hours</label>
                            <div class="row dayBox EveryDayCheck">
                                <div class="col-sm-4">  
                                    <p class="ckBox">
                                        <input type="checkbox" id="test2" name="everyday" value="1"  @if (old('everyday') == "1") checked @endif />
                                        <label for="test2" class="ckColor"> Everyday</label>
                                    </p>    
                                </div>

                                <div class="col-sm-4">  <div class='input-group date datetimepicker1 customsel' >
                                    <input type='text' value="{{ old('everydayStart') }}" name="everydayStart" class="form-control" disabled />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div></div>
                                <div class="col-sm-4"> <div class='input-group date datetimepicker2 customsel' >
                                    <input type='text' value="{{ old('everydayEnd') }}" name="everydayEnd" class="form-control " disabled/>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div></div>
                            </div>

                            <div class="allDay">  
                                <div class="row dayBox">
                                    <div class="col-sm-4">  
                                        <p class="ckBox">
                                            <input type="checkbox" id="mon" name="monday" value="1"  @if (old('monday') == "1") checked @endif  />
                                            <label for="mon" class="ckColor"> Monday</label>
                                        </p>    
                                    </div>


                                    <div class="col-sm-4">  <div class='input-group date datetimepicker1 customsel' >
                                        <input type='text' value="{{ old('mondayStart') }}" name="mondayStart" class="form-control" disabled />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div></div>
                                    <div class="col-sm-4"> <div class='input-group date datetimepicker2 customsel' >
                                        <input type='text' value="{{ old('mondayEnd') }}" name="mondayEnd" class="form-control " disabled/>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div></div>
                                </div>

                                <div class="row dayBox">
                                    <div class="col-sm-4">  
                                        <p class="ckBox">
                                            <input type="checkbox" id="tue" name="tuesday" value="1"  @if (old('tuesday') == "1") checked @endif   />
                                            <label for="tue" class="ckColor"> Tuesday</label>
                                        </p>    
                                    </div>

                                    <div class="col-sm-4">  <div class='input-group date datetimepicker1 customsel' >
                                        <input type='text' value="{{ old('tuesdayStart') }}" name="tuesdayStart" class="form-control" disabled />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div></div>
                                    <div class="col-sm-4"> <div class='input-group date datetimepicker2 customsel' >
                                        <input type='text' value="{{ old('tuesdayEnd') }}" name="tuesdayEnd" class="form-control " disabled/>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div></div>

                                </div>

                                <div class="row dayBox">
                                    <div class="col-sm-4">  
                                        <p class="ckBox">
                                            <input type="checkbox" id="wed" name="wednesday" value="1"  @if (old('wednesday') == "1") checked @endif  />
                                            <label for="wed" class="ckColor"> Wednesday</label>
                                        </p>    
                                    </div>

                                    <div class="col-sm-4">  <div class='input-group date datetimepicker1 customsel' >
                                        <input type='text' value="{{ old('wednesdayStart') }}" name="wednesdayStart" class="form-control" disabled />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div></div>
                                    <div class="col-sm-4"> <div class='input-group date datetimepicker2 customsel' >
                                        <input type='text' value="{{ old('wednesdayEnd') }}" name="wednesdayEnd" class="form-control " disabled/>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div></div>

                                </div>

                                <div class="row dayBox">
                                    <div class="col-sm-4">  
                                        <p class="ckBox">
                                            <input type="checkbox" id="thu" name="thrusday"  value="1"  @if (old('thrusday') == "1") checked @endif   />
                                            <label for="thu" class="ckColor"> Thursday</label>
                                        </p>    
                                    </div>

                                    <div class="col-sm-4">  <div class='input-group date datetimepicker1 customsel' >
                                        <input type='text' value="{{ old('thrusdayStart') }}" name="thrusdayStart" class="form-control" disabled />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div></div>
                                    <div class="col-sm-4"> <div class='input-group date datetimepicker2 customsel' >
                                        <input type='text' value="{{ old('thrusdayEnd') }}" name="thrusdayEnd" class="form-control " disabled/>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div></div>
                                </div>

                                <div class="row dayBox">
                                    <div class="col-sm-4">  
                                        <p class="ckBox">
                                            <input type="checkbox" id="fri" name="friday" value="1"  @if (old('friday') == "1") checked @endif  />
                                            <label for="fri" class="ckColor"> Friday</label>
                                        </p>    
                                    </div>

                                    <div class="col-sm-4">  <div class='input-group date datetimepicker1 customsel' >
                                        <input type='text' value="{{ old('fridayStart') }}" name="fridayStart" class="form-control" disabled />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div></div>
                                    <div class="col-sm-4"> <div class='input-group date datetimepicker2 customsel' >
                                        <input type='text' value="{{ old('fridayEnd') }}" name="fridayEnd" class="form-control " disabled/>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div></div>
                                </div>

                                <div class="row dayBox">
                                    <div class="col-sm-4">  
                                        <p class="ckBox">
                                            <input type="checkbox" id="sat" name="saturday" value="1"  @if (old('saturday') == "1") checked @endif  />
                                            <label for="sat" class="ckColor"> Saturday</label>
                                        </p>    
                                    </div>

                                    <div class="col-sm-4">  <div class='input-group date datetimepicker1 customsel' >
                                        <input type='text' value="{{ old('saturdayStart') }}" name="saturdayStart" class="form-control" disabled />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div></div>
                                    <div class="col-sm-4"> <div class='input-group date datetimepicker2 customsel' >
                                        <input type='text' value="{{ old('saturdayEnd') }}" name="saturdayEnd" class="form-control " disabled/>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div></div>
                                </div>

                                <div class="row dayBox">
                                    <div class="col-sm-4">  
                                        <p class="ckBox">
                                            <input type="checkbox" id="sun" name="sunday" value="1"  @if (old('sunday') == "1") checked @endif  />
                                            <label for="sun" class="ckColor"> Sunday</label>
                                        </p>    
                                    </div>

                                    <div class="col-sm-4">  <div class='input-group date datetimepicker1 customsel' >
                                        <input type='text' value="{{ old('sundayStart') }}" name="sundayStart" class="form-control" disabled />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div></div>
                                    <div class="col-sm-4"> <div class='input-group date datetimepicker2 customsel' >
                                        <input type='text' value="{{ old('sundayEnd') }}" name="sundayEnd" class="form-control " disabled/>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div></div>
                                </div>
                            </div>
                        </div>	
                        <div class="form-group">
                            <label class="mr-0">Office Location Information <i class="optional">(Optional)</i></label>
                            <textarea name="officeLocation" id="officeLocation" class="form-control txtHeight"   data-parsley-required-message="Required"  data-parsley-maxlength="500" data-parsley-maxlength-message="Character limit should be 500 characters." >{{ old('officeLocation') }}</textarea>

                        </div>	
                    </div>

                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="profieBox"></div>
            <div class="clearfix"></div>
            <div class="addBtn DynamicAddder pull-right pd-t-10 "><span class="icon icon-plus"></span>You can add upto 2 more locations</div>
            <div class="clearfix"></div>
            <div  class="pull-right text-right pd-b-20">
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
                            <li data-target="#fade-quote-carousel" data-slide-to="2" ></li>
                            <li data-target="#fade-quote-carousel" data-slide-to="3" ></li>
                            <li data-target="#fade-quote-carousel" data-slide-to="4"></li>
                        </ol>
                        <!-- Carousel items -->
                        <div class="carousel-inner">
                            <div class="active item">

                                <div class="onboard-img" ><img src="{{asset('web/images/create_profile.png')}}" alt=""></div>
                                <h3 class="onboard-title">Lorem ipsum</h3>
                                <blockquote>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem, veritatis nulla eum laudantium totam tempore optio .</p>
                                </blockquote>	
                            </div>
                            <div class="item">

                                <div class="onboard-img"><img src="{{asset('web/images/create_profile.png')}}" alt=""></div>
                                <h3 class="onboard-title">Lorem ipsum</h3>
                                <blockquote>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem, veritatis nulla eum laudantium totam tempore optio </p>
                                </blockquote>
                            </div>
                            <div class=" item">

                                <div class="onboard-img"><img src="{{asset('web/images/create_profile.png')}}" alt=""></div>
                                <h3 class="onboard-title">Lorem ipsum</h3>
                                <blockquote>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem, veritatis nulla eum laudantium totam tempore optio doloremque laboriosam quas, quos eaque molestias odio aut eius animi. Impedit temporibus nisi accusamus.</p>
                                </blockquote>
                            </div>
                            <div class=" item">

                                <div class="onboard-img"><img src="{{asset('web/images/create_profile.png')}}" alt=""></div>
                                <h3 class="onboard-title">Create Your Profile </h3>
                                <blockquote>
                                    <p>Modern medicine has known a rapid progress in the last decades and many traditional forms of treatment have been replaced by new, improved medical…Modern medicine has known a rapid progress in the last decades and many traditional forms.</p>
                                </blockquote>
                            </div>
                            <div class="item">

                                <div class="onboard-img"><img src="{{asset('web/images/create_profile.png')}}" alt=""></div>
                                <h3 class="onboard-title">Lorem ipsum</h3>
                                <blockquote>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem, veritatis nulla eum laudantium totam tempore optio doloremque laboriosam quas, quos eaque molestias odio aut eius animi. Impedit temporibus nisi accusamus.</p>
                                </blockquote>
                            </div>
                        </div>
                        <!-- Controls -->
                        <a class="left carousel-control" href="#fade-quote-carousel"  data-slide="prev">
                            <span class="fa  fa-chevron-left" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="right carousel-control" href="#fade-quote-carousel"  data-slide="next">
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
    <script src ="{{asset('web/scripts/dashboard.js')}}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCsIYaIMo9hd5yEL7pChkVPKPWGX6rFcv8&libraries=places"
    async defer></script>

    <script>
        $("#officeLocation").Editor();
        $("button:submit").click(function(){
            alert('joo');
            $('#officeLocation').text($('#officeLocation').Editor("getText"));
            $('#officeLocation1').text($('#officeLocation1').Editor("getText"));
            $('#officeLocation2').text($('#officeLocation2').Editor("getText"));
        });
    </script>

    <!--&callback=initAutocomplete-->
    @endsection
    @endsection
