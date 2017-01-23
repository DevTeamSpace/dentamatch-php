@extends('web.layouts.dashboard')

@section('content')

<form data-parsley-validate method="post" action="{{url('create-profile')}}">
    {{ csrf_field() }}
    <input type="hidden" name="lat" id="lat">
    <input type="hidden" name="lng" id="lng">
    <input type="hidden" name="full_address" id="full_address">
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

            <div class="commonBox cboxbottom">
                <div class="form-group">
                    <label >Dental Office Name</label>
                    <input type="text" value="{{ old('officeName') }}" onclick="getOfficeName()" id="officeName" name="officeName" class="form-control"  data-parsley-required data-parsley-required-message="office name required">
                </div>
                <div class="form-group">
                    <label  >Dental Office Description</label>
                    <textarea class="form-control  txtHeight"  name="officeDescription"  data-parsley-required data-parsley-required-message="office description required"  data-parsley-maxlength="100" data-parsley-maxlength-message="Character limit should be 500 characters." >{{ old('officeDescription') }}</textarea>
                </div>
            </div>		

            <div class="commonBox cboxbottom masterBox">
                <div class="form-group">
                    <div class="detailTitleBlock">
                        <h5>OFFICE DETAILS</h5>
                    </div>
                    <label >Dental Office Type</label>
                    <div class="slt">
                        <select name="officeType[]" value="" class="ddlCars" multiple="multiple" data-parsley-required data-parsley-required-message="office type required">
                            @foreach($officeType as $office)
                            <option value="{{$office->id}}" >{{$office->officetype_name}}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
                <div class="form-group">
                    <label>Dental Office Address</label>
                    <div id="locationField">
                        <input onFocus="geolocate()" id="autocomplete" name="officeAddress" value="{{ old('officeAddress') }}" type="text" class="form-control"  placeholder="Office name, Street, City, Zip Code and Country" data-parsley-required data-parsley-required-message="office address required">
                    </div>
                </div>

                <table id="address" style="display: none;">
                    <tr>
                        <td class="label">Street address</td>
                        <td class="slimField"><input class="field" id="street_number"
                                                     disabled="true"></input></td>
                        <td class="wideField" colspan="2"><input class="field" id="route"
                                                                 disabled="true"></input></td>
                    </tr>
                    <tr>
                        <td class="label">City</td>
                        <td class="wideField" colspan="3"><input class="field" id="locality"
                                                                 disabled="true"></input></td>
                    </tr>
                    <tr>
                        <td class="label">State</td>
                        <td class="slimField"><input class="field"
                                                     id="administrative_area_level_1" disabled="true"></input></td>
                        <td class="label">Zip code</td>
                        <td class="wideField">                <div class="form-group">
                                <input class="field" id="postal_code" name="postal_code" value="{{ old('postal_code') }}"
                                       disabled="true"></div> </input></td>
                    </tr>
                    <tr>
                        <td class="label">Country</td>
                        <td class="wideField" colspan="1"><input class="field"
                                                                 id="country" disabled="true"></input></td>
                        <td>

                        </td>
                    </tr>
                </table>
                <div class="form-group">
                    <label>Phone Number</label>
<!--                    <input name="phoneNumber" value="{{ old('phoneNumber') }}" type="text" class="form-control" data-parsley-required data-parsley-required-message="phone number required" data-parsley-maxlength="10" data-parsley-maxlength-message="number should be 10" data-parsley-trigger="keyup" data-parsley-type="digits" >-->
                    <input name="phoneNumber" value="{{ old('phoneNumber') }}" type="text" class="form-control" data-parsley-required data-parsley-required-message="phone number required"  data-parsley-trigger="keyup"  data-parsley-pattern="^\(?([0-9]{3})\)([0-9]{3})[-]([0-9]{4})$" data-parsley-pattern-message="pattern should be (123)456-7890" >
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
                        @php
                        $options = '
                        <option value="00:00:00">00:00</option><option  value="00:30:00">00:30</option><option  value="01:00:00">01:00</option><option value="01:30:00">01:30</option><option value="02:00:00">02:00</option><option value="02:30:00">02:30</option><option value="03:00:00">03:00</option><option value="03:30:00">03:30</option><option value="04:00:00">04:00</option><option value="04:30:00">04:30</option>
                        <option value="05:00:00">05:00</option><option value="05:30:00">05:30</option><option value="06:00:00">06:00</option><option value="06:30:00">06:30</option><option value="07:00:00">07:00</option><option value="07:30:00">07:30</option><option value="08:00:00">08:00</option><option value="08:30:00">08:30</option><option value="09:00:00">09:00</option><option value="09:30:00">09:30</option><option value="10:00:00">10:00</option>
                        <option  value="10:30:00">10:30</option><option  value="11:00:00">11:00</option><option  value="11:30:00">11:30</option><option  value="12:00:00">12:00</option><option  value="12:30:00">12:30</option><option  value="13:00:00">13:00</option><option  value="13:30:00">13:30</option><option  value="14:00:00">14:00</option><option  value="14:30:00">14:30</option><option  value="15:00:00">15:00</option>
                        <option  value="15:30:00">15:30</option><option  value="16:00:00">16:00</option><option  value="16:30:00">16:30</option><option  value="17:00:00">17:00</option><option  value="17:30:00">17:30</option><option  value="18:00:00">18:00</option><option  value="18:30:00">18:30</option><option  value="19:00:00">19:00</option><option  value="19:30:00">19:30</option><option  value="20:00:00">20:00</option><option  value="20:30:00">20:30</option>
                        <option  value="21:00:00">21:00</option><option  value="21:30:00">21:30</option><option  value="22:00:00">22:00</option><option  value="22:30:00">22:30</option><option  value="23:00:00">23:00</option><option  value="23:30:00">23:30</option>';
                        @endphp
                        <div class="col-sm-4"><select name="everydayStart" class="form-control customsel"><option value="">Opening Hours</option><?= $options ?></select></div>
                        <div class="col-sm-4"><select name="everydayEnd" class="form-control customsel"><option value="">Closing Hours</option><?= $options ?></select></div>
                    </div>

                    <div class="allDay">  
                        <div class="row dayBox">
                            <div class="col-sm-4">  
                                <p class="ckBox">
                                    <input type="checkbox" id="mon" name="monday" value="1"  @if (old('monday') == "1") checked @endif  />
                                           <label for="mon" class="ckColor"> Monday</label>
                                </p>    
                            </div>
                            <div class="col-sm-4"><select name="mondayStart" class="form-control customsel"><option value="">Opening Hours</option><?= $options ?></select></div>
                            <div class="col-sm-4"><select name="mondayEnd" class="form-control customsel"><option value="">Closing Hours</option><?= $options ?></select></div>
                        </div>

                        <div class="row dayBox">
                            <div class="col-sm-4">  
                                <p class="ckBox">
                                    <input type="checkbox" id="tue" name="tuesday" value="1"  @if (old('tuesday') == "1") checked @endif   />
                                           <label for="tue" class="ckColor"> Tuesday</label>
                                </p>    
                            </div>
                            <div class="col-sm-4"><select name="tuesdayStart" class="form-control customsel"><option value="">Opening Hours</option><?= $options ?></select></div>
                            <div class="col-sm-4"><select name="tuesdayEnd" class="form-control customsel"><option value="">Closing Hours</option><?= $options ?></select></div>

                        </div>

                        <div class="row dayBox">
                            <div class="col-sm-4">  
                                <p class="ckBox">
                                    <input type="checkbox" id="wed" name="wednesday" value="1"  @if (old('wednesday') == "1") checked @endif  />
                                           <label for="wed" class="ckColor"> Wednesday</label>
                                </p>    
                            </div>
                            <div class="col-sm-4"><select name="wednesdayStart" class="form-control customsel"><option value="">Opening Hours</option><?= $options ?></select></div>
                            <div class="col-sm-4"><select name="wednesdayEnd" class="form-control customsel"><option value="">Closing Hours</option><?= $options ?></select></div>

                        </div>

                        <div class="row dayBox">
                            <div class="col-sm-4">  
                                <p class="ckBox">
                                    <input type="checkbox" id="thu" name="thrusday"  value="1"  @if (old('thrusday') == "1") checked @endif   />
                                           <label for="thu" class="ckColor"> Thursday</label>
                                </p>    
                            </div>
                            <div class="col-sm-4"><select name="thrusdayStart" class="form-control customsel"><option value="">Opening Hours</option><?= $options ?></select></div>
                            <div class="col-sm-4"><select name="thrusdayEnd" class="form-control customsel"><option value="">Closing Hours</option><?= $options ?></select></div>
                        </div>

                        <div class="row dayBox">
                            <div class="col-sm-4">  
                                <p class="ckBox">
                                    <input type="checkbox" id="fri" name="friday" value="1"  @if (old('friday') == "1") checked @endif  />
                                           <label for="fri" class="ckColor"> Friday</label>
                                </p>    
                            </div>
                            <div class="col-sm-4"><select name="fridayStart" class="form-control customsel"><option value="">Opening Hours</option><?= $options ?></select></div>
                            <div class="col-sm-4"><select name="fridayEnd" class="form-control customsel"><option value="">Closing Hours</option><?= $options ?></select></div>
                        </div>

                        <div class="row dayBox">
                            <div class="col-sm-4">  
                                <p class="ckBox">
                                    <input type="checkbox" id="sat" name="saturday" value="1"  @if (old('saturday') == "1") checked @endif  />
                                           <label for="sat" class="ckColor"> Saturday</label>
                                </p>    
                            </div>
                            <div class="col-sm-4"><select name="saturdayStart" class="form-control customsel"><option value="">Opening Hours</option><?= $options ?></select></div>
                            <div class="col-sm-4"><select name="saturdayEnd" class="form-control customsel"><option value="">Closing Hours</option><?= $options ?></select></div>
                        </div>

                        <div class="row dayBox">
                            <div class="col-sm-4">  
                                <p class="ckBox">
                                    <input type="checkbox" id="sun" name="sunday" value="1"  @if (old('sunday') == "1") checked @endif  />
                                           <label for="sun" class="ckColor"> Sunday</label>
                                </p>    
                            </div>
                            <div class="col-sm-4"><select name="sundayStart" class="form-control customsel"><option value="">Opening Hours</option><?= $options ?></select></div>
                            <div class="col-sm-4"><select name="sundayEnd" class="form-control customsel"><option value="">Closing Hours</option><?= $options ?></select></div>
                        </div>
                    </div>
                </div>	
                <div class="form-group">
                    <label>Office Location Information <i class="optional">(Optional)</i></label>
                    <textarea name="officeLocation" class="form-control txtHeight"   data-parsley-required-message="location information required"  data-parsley-maxlength="100" data-parsley-maxlength-message="Character limit should be 500 characters." >{{ old('officeLocation') }}</textarea>
                </div>	
            </div>			
        </div>
        <div class="pull-right text-right">
<!--            <div class="addBtn DynamicAdd"><span class="icon icon-plus"></span>Add total of 1 locations</div>-->
            <button type="submit" class="btn btn-primary pd-l-40 pd-r-40">Save</button>
        </div>
    </div>
</form>

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
<script>
		$("#fade-quote-carousel").carousel({
			interval: false,
			wrap: false
		});
		var checkitem = function() {
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
    $('.ddlCars').multiselect({
        numberDisplayed: 3,
    });

</script>



<script>
    // This example displays an address form, using the autocomplete feature
    // of the Google Places API to help users fill in the information.

    // This example requires the Places library. Include the libraries=places
    // parameter when you first load the API. For example:
    // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

    var placeSearch, autocomplete, officeName;
    var componentForm = {
        street_number: 'short_name',
        route: 'long_name',
        locality: 'long_name',
        administrative_area_level_1: 'short_name',
        country: 'long_name',
        postal_code: 'short_name'
    };

    function initAutocomplete() {
        // Create the autocomplete object, restricting the search to geographical
        // location types.
        autocomplete = new google.maps.places.Autocomplete(
                /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
                {types: ['geocode']});

        // When the user selects an address from the dropdown, populate the address
        // fields in the form.
        autocomplete.addListener('place_changed', fillInAddress);
    }

    function fillInAddress() {
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();
//console.log(place);
        for (var component in componentForm) {
            document.getElementById(component).value = '';
            document.getElementById(component).disabled = false;
        }
        // Get each component of the address from the place details
        // and fill the corresponding field on the form.
        for (var i = 0; i < place.address_components.length; i++) {
            var addressType = place.address_components[i].types[0];
            if (componentForm[addressType]) {
                var val = place.address_components[i][componentForm[addressType]];
                document.getElementById(addressType).value = val;
            }
        }

        document.getElementById('full_address').value = place.formatted_address;
        document.getElementById('lat').value = place.geometry.location.lat();
        document.getElementById('lng').value = place.geometry.location.lng();

    }

    // Bias the autocomplete object to the user's geographical location,
    // as supplied by the browser's 'navigator.geolocation' object.
    function geolocate() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                var geolocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                var circle = new google.maps.Circle({
                    center: geolocation,
                    radius: position.coords.accuracy
                });
                autocomplete.setBounds(circle.getBounds());
            });
        }
    }

    function getOfficeName() {
        officeName = new google.maps.places.Autocomplete(
                (document.getElementById('officeName')),
                {types: ['geocode']});
        officeName.addListener('place_changed', fillOfficeAddress);
    }

    function fillOfficeAddress() {
        var addy = $('#officeName').val();
        var offName = addy.substr(0, addy.indexOf(','));
        document.getElementById('officeName').value = offName;
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCsIYaIMo9hd5yEL7pChkVPKPWGX6rFcv8&libraries=places&callback=initAutocomplete"
async defer></script>

@endsection
@endsection