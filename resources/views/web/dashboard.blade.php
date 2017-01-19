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
                    <input type="text" value="{{ old('officeName') }}" name="officeName" class="form-control"  data-parsley-required data-parsley-required-message="office name required">
                </div>
                <div class="form-group">
                    <label  >Dental Office Description</label>
                    <textarea class="form-control  txtHeight" value="{{ old('officeDescription') }}" name="officeDescription"  data-parsley-required data-parsley-required-message="office description required"  data-parsley-maxlength="100" data-parsley-maxlength-message="Charcter should be 500" ></textarea>
                </div>
            </div>		

            <div class="commonBox cboxbottom masterBox">
                <div class="form-group">
                    <div class="detailTitleBlock">
                        <h5>OFFICE DETAILS</h5>
                    </div>
                    <label >Dental Office Type</label>
                    <div class="slt">
                        <select name="officeType" value="{{ old('officeType') }}" class="ddlCars" multiple="multiple" data-parsley-required data-parsley-required-message="office type required">
                            @foreach($officeType as $office)
                            <option value="{{$office->id}}">{{$office->officetype_name}}</option>
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
                                <input class="field" id="postal_code" name="postal_code"
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
                    <input name="phoneNumber" value="{{ old('phoneNumber') }}" type="text" class="form-control" data-parsley-required data-parsley-required-message="phone number required" data-parsley-maxlength="10" data-parsley-maxlength-message="number should be 10" data-parsley-trigger="keyup" data-parsley-type="digits" >
                </div>

                <div class="form-group">
                    <label >Working Hours</label>
                    <div class="row dayBox EveryDayCheck">
                        <div class="col-sm-4">  
                            <p class="ckBox">
                                <input type="checkbox" id="test2" name="everyday" value="{{ old('everyday') }}" />
                                <label for="test2" class="ckColor"> Everyday</label>
                            </p>    
                        </div>
                        <div class="col-sm-4"><input value="{{ old('everydayStart') }}" name="everydayStart" type="text" class="form-control" placeholder="Opening Hours"  data-parsley-required-message="opening hours required"></div>
                        <div class="col-sm-4"><input value="{{ old('everydayEnd') }}" name="everydayEnd" type="text" class="form-control" placeholder="Closing Hours"  data-parsley-required-message="closing hours required" ></div>
                    </div>

                    <div class="allDay">  
                        <div class="row dayBox">
                            <div class="col-sm-4">  
                                <p class="ckBox">
                                    <input type="checkbox" id="mon" name="monday" value="{{ old('monday') }}" />
                                    <label for="mon" class="ckColor"> Monday</label>
                                </p>    
                            </div>
                            <div class="col-sm-4"><input value="{{ old('mondayStart') }}" name="mondayStart" type="text" class="form-control" placeholder="Opening Hours"  data-parsley-required-message="opening hours required"></div>
                            <div class="col-sm-4"><input value="{{ old('mondayEnd') }}" name="mondayEnd" type="text" class="form-control" placeholder="Closing Hours"  data-parsley-required-message="closing hours required" ></div>
                        </div>

                        <div class="row dayBox">
                            <div class="col-sm-4">  
                                <p class="ckBox">
                                    <input type="checkbox" id="tue" name="tuesday" value="{{ old('tuesday') }}"  />
                                    <label for="tue" class="ckColor"> Tuesday</label>
                                </p>    
                            </div>
                            <div class="col-sm-4"><input value="{{ old('tuesdayStart') }}" name="tuesdayStart" type="text" class="form-control" placeholder="Opening Hours"  data-parsley-required-message="opening hours required"></div>
                            <div class="col-sm-4"><input value="{{ old('tuesdayEnd') }}" name="tuesdayEnd" type="text" class="form-control" placeholder="Closing Hours"   data-parsley-required-message="closing hours required" ></div>
                        </div>

                        <div class="row dayBox">
                            <div class="col-sm-4">  
                                <p class="ckBox">
                                    <input type="checkbox" id="wed" name="wednesday" value="{{ old('wednesday') }}" />
                                    <label for="wed" class="ckColor"> Wednesday</label>
                                </p>    
                            </div>
                            <div class="col-sm-4"><input value="{{ old('wednesdayStart') }}" name="wednesdayStart" type="text" class="form-control" placeholder="Opening Hours"  data-parsley-required-message="opening hours required"></div>
                            <div class="col-sm-4"><input value="{{ old('wednesdayEnd') }}" name="wednesdayEnd" type="text" class="form-control" placeholder="Closing Hours"  data-parsley-required-message="closing hours required" ></div>
                        </div>

                        <div class="row dayBox">
                            <div class="col-sm-4">  
                                <p class="ckBox">
                                    <input type="checkbox" id="thu" name="thrusday"  value="{{ old('thrusday') }}"  />
                                    <label for="thu" class="ckColor"> Thursday</label>
                                </p>    
                            </div>
                            <div class="col-sm-4"><input value="{{ old('thrusdayStart') }}" name="thrusdayStart" type="text" class="form-control" placeholder="Opening Hours"  data-parsley-required-message="opening hours required"></div>
                            <div class="col-sm-4"><input value="{{ old('thrusdayEnd') }}" name="thrusdayEnd" type="text" class="form-control" placeholder="Closing Hours" data-parsley-required-message="closing hours required"  ></div>
                        </div>

                        <div class="row dayBox">
                            <div class="col-sm-4">  
                                <p class="ckBox">
                                    <input type="checkbox" id="fri" name="friday" value="{{ old('friday') }}" />
                                    <label for="fri" class="ckColor"> Friday</label>
                                </p>    
                            </div>
                            <div class="col-sm-4"><input value="{{ old('fridayStart') }}" name="fridayStart" type="text" class="form-control" placeholder="Opening Hours"  data-parsley-required-message="opening hours required"></div>
                            <div class="col-sm-4"><input value="{{ old('fridayEnd') }}" name="fridayEnd" type="text" class="form-control" placeholder="Closing Hours"  data-parsley-required-message="closing hours required" ></div>
                        </div>

                        <div class="row dayBox">
                            <div class="col-sm-4">  
                                <p class="ckBox">
                                    <input type="checkbox" id="sat" name="saturday" value="{{ old('saturday') }}" />
                                    <label for="sat" class="ckColor"> Saturday</label>
                                </p>    
                            </div>
                            <div class="col-sm-4"><input value="{{ old('saturdayStart') }}" name="saturdayStart" type="text" class="form-control" placeholder="Opening Hours"  data-parsley-required-message="opening hours required"></div>
                            <div class="col-sm-4"><input value="{{ old('saturdayEnd') }}" name="saturdayEnd" type="text" class="form-control" placeholder="Closing Hours"  data-parsley-required-message="closing hours required" ></div>
                        </div>

                        <div class="row dayBox">
                            <div class="col-sm-4">  
                                <p class="ckBox">
                                    <input type="checkbox" id="sun" name="sunday" value="{{ old('sunday') }}" />
                                    <label for="sun" class="ckColor"> Sunday</label>
                                </p>    
                            </div>
                            <div class="col-sm-4"><input value="{{ old('sundayStart') }}" name="sundayStart" type="text" class="form-control" placeholder="Opening Hours"  data-parsley-required-message="opening hours required"></div>
                            <div class="col-sm-4"><input value="{{ old('sundayEnd') }}" name="sundayEnd" type="text" class="form-control" placeholder="Closing Hours"  data-parsley-required-message="closing hours required" ></div>
                        </div>
                    </div>
                </div>	
                <div class="form-group">
                    <label>Office Location Information <i class="optional">(Optional)</i></label>
                    <textarea class="form-control txtHeight"   data-parsley-required-message="location information required"  data-parsley-maxlength="100" data-parsley-maxlength-message="Charcter should be 500" ></textarea>
                </div>	
            </div>			
        </div>
        <div class="pull-right text-right">
            <div class="addBtn DynamicAdd"><span class="icon icon-plus"></span>Add total of 1 locations</div>
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
                        <li data-target="#fade-quote-carousel" data-slide-to="0"></li>
                        <li data-target="#fade-quote-carousel" data-slide-to="1"></li>
                        <li data-target="#fade-quote-carousel" data-slide-to="2" ></li>
                        <li data-target="#fade-quote-carousel" data-slide-to="3" class="active"></li>
                        <li data-target="#fade-quote-carousel" data-slide-to="4"></li>
                    </ol>
                    <!-- Carousel items -->
                    <div class="carousel-inner">
                        <div class="item">

                            <div class="onboard-img" ><img src="" alt=""></div>
                            <h3 class="onboard-title">Lorem ipsum</h3>
                            <blockquote>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem, veritatis nulla eum laudantium totam tempore optio .</p>
                            </blockquote>	
                        </div>
                        <div class="item">

                            <div class="onboard-img"><img src="" alt=""></div>
                            <h3 class="onboard-title">Lorem ipsum</h3>
                            <blockquote>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem, veritatis nulla eum laudantium totam tempore optio </p>
                            </blockquote>
                        </div>
                        <div class=" item">

                            <div class="onboard-img"><img src="" alt=""></div>
                            <h3 class="onboard-title">Lorem ipsum</h3>
                            <blockquote>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem, veritatis nulla eum laudantium totam tempore optio doloremque laboriosam quas, quos eaque molestias odio aut eius animi. Impedit temporibus nisi accusamus.</p>
                            </blockquote>
                        </div>
                        <div class="active item">

                            <div class="onboard-img"><img src="{{asset('web/images/create_profile.png')}}" alt=""></div>
                            <h3 class="onboard-title">Create Your Profile </h3>
                            <blockquote>
                                <p>Modern medicine has known a rapid progress in the last decades and many traditional forms of treatment have been replaced by new, improved medical…Modern medicine has known a rapid progress in the last decades and many traditional forms.</p>
                            </blockquote>
                        </div>
                        <div class="item">

                            <div class="onboard-img"><img src="" alt=""></div>
                            <h3 class="onboard-title">Lorem ipsum</h3>
                            <blockquote>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem, veritatis nulla eum laudantium totam tempore optio doloremque laboriosam quas, quos eaque molestias odio aut eius animi. Impedit temporibus nisi accusamus.</p>
                            </blockquote>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>-->
@section('js')
<script>
    $('.carousel').carousel();
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

    var placeSearch, autocomplete;
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
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCsIYaIMo9hd5yEL7pChkVPKPWGX6rFcv8&libraries=places&callback=initAutocomplete"
async defer></script>

@endsection
@endsection