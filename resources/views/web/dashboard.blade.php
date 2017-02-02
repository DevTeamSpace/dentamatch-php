@extends('web.layouts.dashboard')

@section('content')

<style>
    .pac-container:after{
        content:none !important;
    }
</style>

<div class="customContainer center-block containerBottom">
    <div class="profieBox">
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
                    <input type="text" value="{{ old('officeName') }}" onclick="getOfficeName()" id="officeName" name="officeName" class="form-control txtBtnDisable"  data-parsley-required data-parsley-required-message="required">
                </div>
                <div class="form-group">
                    <label  >Dental Office Description</label>
                    <textarea class="form-control  txtHeight txtBtnDisable chacterValidtion"  name="officeDescription"  data-parsley-required data-parsley-required-message="required" maxlength=500 >{{ old('officeDescription') }}</textarea>
                </div>
            </div>	
            <div  class="pull-right text-right pd-b-20">
                <button id="createProfileButton" type="submit" class="btn btn-primary pd-l-40 pd-r-40">Save</button>
            </div>
            <div class="clearfix"></div>
        </form>
        <div class="mainMasterBox">
        <form data-parsley-validate method="post" id="officeDetailForm" action="javascript:void(0);">
            {{ csrf_field() }}
            <div id="officeDetail-errors"></div>
            <input type="hidden" name="lat" id="lat">
            <input type="hidden" name="lng" id="lng">
            <input type="hidden" name="full_address" id="full_address">
            <input type="hidden" id="postal_code" data-parsley-required  name="postal_code">

            <div class="commonBox cboxbottom masterBox">
                <div class="form-group">
                    <div class="detailTitleBlock">
                        <h5>OFFICE DETAILS</h5>
                    </div>
                    <label >Dental Office Type</label>
                    <div class="slt">
                        <select style="display:none;" name="officeType[]" value="" class="ddlCars" multiple="multiple" data-parsley-required data-parsley-required-message="required">
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
                        <input  id="autocomplete" name="officeAddress" value="{{ old('officeAddress') }}" type="text" class="form-control"  placeholder="Office name, Street, City, Zip Code and Country" data-parsley-required data-parsley-required-message="required">
                    </div>
                </div>
                <div id="location-msg"></div>

                <div class="form-group">
                    <label>Phone Number</label>
                    <input id="phoneNumber" name="phoneNumber" value="{{ old('phoneNumber') }}" type="text" class="form-control phone-number" data-parsley-required data-parsley-required-message="required"   data-parsley-trigger="keyup" data-parsley-minlength="14"   data-parsley-minlength-message="phone number should be 10 digit"   >
<!--                    <input id="phoneNumber" name="phoneNumber" value="{{ old('phoneNumber') }}" type="text" class="form-control" data-parsley-required data-parsley-required-message="phone number required" data-parsley-maxlength="13" data-parsley-maxlength-message="number should be 10" data-parsley-trigger="keyup"  >-->
<!--                    <input name="phoneNumber" value="{{ old('phoneNumber') }}" type="text" class="form-control" data-parsley-required data-parsley-required-message="phone number required"  data-parsley-trigger="keyup"  data-parsley-pattern="^\(?([0-9]{3})\)([0-9]{3})[-]([0-9]{4})$" data-parsley-pattern-message="pattern should be (123)456-7890" >-->
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
                        <!--                        @php
                                                $options = '
                                                <option value="00:00:00">00:00</option><option  value="00:30:00">00:30</option><option  value="01:00:00">01:00</option><option value="01:30:00">01:30</option><option value="02:00:00">02:00</option><option value="02:30:00">02:30</option><option value="03:00:00">03:00</option><option value="03:30:00">03:30</option><option value="04:00:00">04:00</option><option value="04:30:00">04:30</option>
                                                <option value="05:00:00">05:00</option><option value="05:30:00">05:30</option><option value="06:00:00">06:00</option><option value="06:30:00">06:30</option><option value="07:00:00">07:00</option><option value="07:30:00">07:30</option><option value="08:00:00">08:00</option><option value="08:30:00">08:30</option><option value="09:00:00">09:00</option><option value="09:30:00">09:30</option><option value="10:00:00">10:00</option>
                                                <option  value="10:30:00">10:30</option><option  value="11:00:00">11:00</option><option  value="11:30:00">11:30</option><option  value="12:00:00">12:00</option><option  value="12:30:00">12:30</option><option  value="13:00:00">13:00</option><option  value="13:30:00">13:30</option><option  value="14:00:00">14:00</option><option  value="14:30:00">14:30</option><option  value="15:00:00">15:00</option>
                                                <option  value="15:30:00">15:30</option><option  value="16:00:00">16:00</option><option  value="16:30:00">16:30</option><option  value="17:00:00">17:00</option><option  value="17:30:00">17:30</option><option  value="18:00:00">18:00</option><option  value="18:30:00">18:30</option><option  value="19:00:00">19:00</option><option  value="19:30:00">19:30</option><option  value="20:00:00">20:00</option><option  value="20:30:00">20:30</option>
                                                <option  value="21:00:00">21:00</option><option  value="21:30:00">21:30</option><option  value="22:00:00">22:00</option><option  value="22:30:00">22:30</option><option  value="23:00:00">23:00</option><option  value="23:30:00">23:30</option>';
                                                @endphp-->
                        <!--                        <div class="col-sm-4"><select name="everydayStart" class="form-control customsel"><option value="">Opening Hours</option><?= $options ?></select></div>
                                                <div class="col-sm-4"><select name="everydayEnd" class="form-control customsel"><option value="">Closing Hours</option><?= $options ?></select></div>-->
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
<!--                            <div class="col-sm-4"><select name="mondayStart" class="form-control customsel"><option value="">Opening Hours</option><?= $options ?></select></div>
                            <div class="col-sm-4"><select name="mondayEnd" class="form-control customsel"><option value="">Closing Hours</option><?= $options ?></select></div>-->

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
<!--                            <div class="col-sm-4"><select name="tuesdayStart" class="form-control customsel"><option value="">Opening Hours</option><?= $options ?></select></div>
                            <div class="col-sm-4"><select name="tuesdayEnd" class="form-control customsel"><option value="">Closing Hours</option><?= $options ?></select></div>-->
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
<!--                            <div class="col-sm-4"><select name="wednesdayStart" class="form-control customsel"><option value="">Opening Hours</option><?= $options ?></select></div>
                            <div class="col-sm-4"><select name="wednesdayEnd" class="form-control customsel"><option value="">Closing Hours</option><?= $options ?></select></div>-->
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
<!--                            <div class="col-sm-4"><select name="thrusdayStart" class="form-control customsel"><option value="">Opening Hours</option><?= $options ?></select></div>
                            <div class="col-sm-4"><select name="thrusdayEnd" class="form-control customsel"><option value="">Closing Hours</option><?= $options ?></select></div>-->
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
<!--                            <div class="col-sm-4"><select name="fridayStart" class="form-control customsel"><option value="">Opening Hours</option><?= $options ?></select></div>
                            <div class="col-sm-4"><select name="fridayEnd" class="form-control customsel"><option value="">Closing Hours</option><?= $options ?></select></div>-->
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
<!--                            <div class="col-sm-4"><select name="saturdayStart" class="form-control customsel"><option value="">Opening Hours</option><?= $options ?></select></div>
                            <div class="col-sm-4"><select name="saturdayEnd" class="form-control customsel"><option value="">Closing Hours</option><?= $options ?></select></div>-->
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
<!--                            <div class="col-sm-4"><select name="sundayStart" class="form-control customsel"><option value="">Opening Hours</option><?= $options ?></select></div>
                            <div class="col-sm-4"><select name="sundayEnd" class="form-control customsel"><option value="">Closing Hours</option><?= $options ?></select></div>-->
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
                    <label>Office Location Information <i class="optional">(Optional)</i></label>
                    <textarea name="officeLocation" class="form-control txtHeight"   data-parsley-required-message="location information required"  data-parsley-maxlength="500" data-parsley-maxlength-message="Character limit should be 500 characters." >{{ old('officeLocation') }}</textarea>
                </div>	
            </div>
            <div id="removeButton" class="pull-right text-right pd-b-15">
                <button  id="officeDetailButton" type="submit" class="btn btn-primary pd-l-40 pd-r-40 formBtnAction">Save</button>
            </div>
            <div class="clearfix"></div>
    </div>
</form>
		</div>
        <div class="clearfix"></div>
        <div class="addBtn DynamicAddder pull-right pd-t-10 "><span class="icon icon-plus"></span>You can add upto 2 more locations</div>

</div>

@if(isset($modal))
<!-- Modal -->
<div id="onboardView" class="modal fade" role="dialog">
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
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#fade-quote-carousel"  data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
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
<script type="text/javascript">
		
    $('.ddlCars').multiselect({
        numberDisplayed: 3,

	});
</script>

<script>
    $("#fade-quote-carousel").carousel({
        interval: false,
        wrap: false
    });
    var checkitem = function () {
        var $this;
        $this = $("#fade-quote-carousel");
        if ($("#fade-quote-carousel .carousel-inner .item:first").hasClass("active")) {
            $this.children(".left").hide();
            $this.children(".right").show();
        } else if ($("#fade-quote-carousel .carousel-inner .item:last").hasClass("active")) {
            $this.children(".right").hide();
            $this.children(".left").show();
        } else {
            $this.children(".carousel-control").show();
        }
    };

    checkitem();

    $("#fade-quote-carousel").on("slid.bs.carousel", "", checkitem);
</script>

<script>

    $(function () {

        /*code for linked picker - curtainup and curtaindown*/
        var $startTime1 = $('.datetimepicker1');
        var $endTime1 = $('.datetimepicker2');

        $startTime1.datetimepicker({
            format: 'hh:mm A',
			'allowInputToggle' : true,
			
//		defaultDate: new Date(),
            //ignoreReadonly: true,
            minDate: moment().startOf('day'),
            maxDate: moment().endOf('day')
        });

        $endTime1.datetimepicker({
            format: 'hh:mm A',
			'allowInputToggle' : true,
//		defaultDate: $startTime1.data("DateTimePicker").date().add(1, 'minutes'),
//		useCurrent: false,
            //ignoreReadonly: true,
            minDate: moment().startOf('day'),
            maxDate: moment().endOf('day')
        });

//	$startTime1.data("DateTimePicker").maxDate($endTime1.data("DateTimePicker").date().subtract(1, 'minutes'));
//	$endTime1.data("DateTimePicker").minDate($startTime1.data("DateTimePicker").date().add(1, 'minutes'));
//


        /*End of timepicker*/
    });

</script>

<script>

    $('.datetimepicker1').on("dp.change", function () {

        var date = $(this).data('date');

        $(this).parents(".row").find('.datetimepicker2').data('DateTimePicker').minDate(date);
        console.log(date);
    });
    $('.datetimepicker2').on("dp.change", function () {
        var date = $(this).data('date');
        $(this).parents(".row").find('.datetimepicker1').data('DateTimePicker').maxDate(date);
        console.log(date);
    });


</script>


<script>
    var placeSearch, autocomplete, autocomplete1, autocomplete2, officeName;
    var componentForm = {
        postal_code: 'short_name'
    };

    var autocomplete = {};
    var autocompletesWraps = ['autocomplete', 'autocomplete1', 'autocomplete2'];

    function initializeMap() {

        $.each(autocompletesWraps, function (index, name) {
            if ($('#' + name).length == 0) {
                return;
            }

            autocomplete[name] = new google.maps.places.SearchBox($('#' + name)[0], {types: ['geocode']});
            autocomplete[name].addListener('places_changed', function () {
                var allPlace = autocomplete[name].getPlaces();
                console.log(name);
                var indexField = name.split('autocomplete')[1];
                allPlace.forEach(function (place) {


                    for (var i = 0; i < place.address_components.length; i++) {
                        var addressType = place.address_components[i].types[0];
                        if (componentForm[addressType]) {
                            var val = place.address_components[i][componentForm[addressType]];
                            document.getElementById(addressType + indexField).value = val;
                        }
                    }

                    document.getElementById('full_address' + indexField).value = place.formatted_address;
                    document.getElementById('lat' + indexField).value = place.geometry.location.lat();
                    document.getElementById('lng' + indexField).value = place.geometry.location.lng();
                    $('#' + name)[0].value = place.formatted_address;

                    checkLocation($('#postal_code' + indexField).val(), indexField);
                });
            });
        });
    }

    $(window).load(function () {
        initializeMap();
    });


    function getOfficeName() {
        officeName = new google.maps.places.SearchBox(
                (document.getElementById('officeName')),
                {types: ['geocode']});
        officeName.addListener('places_changed', fillOfficeAddress);
    }

    function fillOfficeAddress() {
        var addy = $('#officeName').val();
        var offName = addy.substr(0, addy.indexOf(','));
        document.getElementById('officeName').value = offName;
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCsIYaIMo9hd5yEL7pChkVPKPWGX6rFcv8&libraries=places"
async defer></script>

<!--&callback=initAutocomplete-->
@endsection
@endsection
