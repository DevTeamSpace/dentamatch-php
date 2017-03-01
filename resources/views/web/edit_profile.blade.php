@extends('web.layouts.dashboard')

@section('content')
<style>
    .pac-container:after{
        content:none !important;
    }
</style>
<div class="container globalpadder">
    <!-- Tab-->
    <div class="row" id="edit-profile">
        @include('web.layouts.sidebar')
        <div class="col-sm-8 ">
            <div class="addReplica">

                <form data-parsley-validate="" id="createProfileForm" novalidate=""  class="formdataPart" action="javascript:void(0);">	
                    <div class="resp-tabs-container commonBox profilePadding cboxbottom ">
                        <div class="descriptionBox">
                            <div class="dropdown icon-upload-ctn1">
                                <span class="icon-upload-detail dropdown-toggle" data-toggle="dropdown" aria-expanded="true"></span>
                                <ul class="actions text-left dropdown-menu">
                                    <li><span class="gbllist"><i class="icon icon-edit"></i> Edit</span></li>
                                </ul>
                            </div>
                            <div class="viewProfileRightCard">
                                <h6>Dental Office Name</h6>
                                <div class="detailTitleBlock">
                                    <h5 data-bind="text: dentalOfficeName"></h5>
                                </div>
                                <h6>Dental Office Description</h6>
                                <p data-bind="text: dentalOfficeDescription"></p>
                            </div>
                        </div>
                    </div>
                </form>

                <!--ko foreach: offices-->
                    <div class="resp-tabs-container commonBox replicaBox profilePadding cboxbottom masterBox" data-bind="visible: showOffice">
                        <div class="descriptionBox">
                            <div class="dropdown icon-upload-ctn1">
                                <span class="icon-upload-detail dropdown-toggle" data-toggle="dropdown" aria-expanded="true"></span>
                                <ul class="actions text-left dropdown-menu">
                                    <li><span class="gbllist"><i class="icon icon-edit "></i> Edit</span></li>
                                    <li><span class="gbllist"><i class="icon icon-deleteicon"></i> Delete</span></li>
                                </ul>
                            </div>
                            <div class="descriptionBoxInner">
                                <div class="viewProfileRightCard pd-b-25">
                                    <div class="detailTitleBlock">
                                        <h5>OFFICE DETAILS</h5>
                                    </div>
                                    <h6>Dental Office Type</h6>
                                    <span></span>
                                    <!--ko foreach: officeType-->
                                    <span data-bind="text: $data"></span><span data-bind="text: $index() !== ($parent.officeType().length -1) ? ',' : ''"></span>
                                    <!--/ko-->
                                </div>
                                <div class="viewProfileRightCard pd-b-25">
                                    <h6>Dental Office Address</h6>
                                    <p data-bind="text: officeAddress"></p>
                                </div>
                                <div class="viewProfileRightCard pd-b-25">
                                    <h6>Phone Number</h6>
                                    <p data-bind="text: officePhone"></p>
                                </div>
                                <div class="viewProfileRightCard pd-b-25">
                                    <h6>Working Hours</h6>
                                    <!--ko if: officeWorkingHours.isEverydayWork() === true-->
                                    <p>Everyday : <span data-bind="text: officeWorkingHours.everydayStart() + ' '"></span> to <span data-bind="text: officeWorkingHours.everydayEnd()"></span></p>
                                    <!--/ko-->
                                    <!--ko if: officeWorkingHours.isMondayWork() === true-->
                                    <p>Monday : <span data-bind="text: officeWorkingHours.mondayStart() + ' '"></span> to <span data-bind="text: officeWorkingHours.mondayEnd()"></span></p>
                                    <!--/ko-->
                                    <!--ko if: officeWorkingHours.isTuesdayWork() === true-->
                                    <p>Tuesday : <span data-bind="text: officeWorkingHours.tuesdayStart() + ' '"></span> to <span data-bind="text: officeWorkingHours.tuesdayEnd()"></span></p>
                                    <!--/ko-->
                                    <!--ko if: officeWorkingHours.isWednesdayWork() === true-->
                                    <p>Wednesday : <span data-bind="text: officeWorkingHours.wednesdayStart() + ' '"></span> to <span data-bind="text: officeWorkingHours.wednesdayEnd()"></span></p>
                                    <!--/ko-->
                                    <!--ko if: officeWorkingHours.isThursdayWork() === true-->
                                    <p>Thursday : <span data-bind="text: officeWorkingHours.thursdayStart() + ' '"></span> to <span data-bind="text: officeWorkingHours.thursdayEnd()"></span></p>
                                    <!--/ko-->
                                    <!--ko if: officeWorkingHours.isFridayWork() === true-->
                                    <p>Friday : <span data-bind="text: officeWorkingHours.fridayStart() + ' '"></span> to <span data-bind="text: officeWorkingHours.fridayEnd()"></span></p>
                                    <!--/ko-->
                                    <!--ko if: officeWorkingHours.isSaturdayWork() === true-->
                                    <p>Saturday : <span data-bind="text: officeWorkingHours.saturdayStart() + ' '"></span> to <span data-bind="text: officeWorkingHours.saturdayEnd()"></span></p>
                                    <!--/ko-->
                                    <!--ko if: officeWorkingHours.isSundayWork() === true-->
                                    <p>Sunday : <span data-bind="text: officeWorkingHours.sundayStart() + ' '"></span> to <span data-bind="text: officeWorkingHours.sundayEnd()"></span></p>
                                    <!--/ko-->
                                </div>
                                <div class="viewProfileRightCard pd-b-25">
                                    <h6>Office Location Information</h6>
                                    <p data-bind="text: officeInfo"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <form data-parsley-validate novalidate=""  class="formdataPart" data-bind="visible: showOfficeEditForm">
                    <div class="commonBox cboxbottom masterBox" data-bind="template: {name: 'profileEditForm', data: $data}">
                        
                    </div>
                </form>
                <!--/ko-->
            </div>
            <br>
            <div class="pull-right text-right">
                <div class="addProfileBtn "><span class="icon icon-plus"></span>Add total of 1 locations</div>
            </div>
        </div>
        
        <script type="text/html" id="profileEditForm">
                <div class="form-group">
                    <div class="detailTitleBlock">
                        <h5>OFFICE DETAILS</h5>
                    </div>
                    <label >Dental Office Type</label>
                    <div class="slt">
                        <select class="ddlCars" multiple="true" data-bind=" options: $root.allOfficeTypes, selectedOptions: officeType ">
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Dental Office Address</label>
                    <input type="text" value="" id="officeAddress" name="officeName" class="form-control txtBtnDisable"  data-parsley-required data-parsley-required-message="Required" data-bind="click: $root.getOfficeName, value: officeAddress, event: {change: $root.getOfficeName}">
                    <p class="error-div" ></p>
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" class="form-control" data-parsley-required data-parsley-required-message="phone number required" data-parsley-maxlength="10" data-parsley-maxlength-message="number should be 10" data-parsley-trigger="keyup" data-parsley-type="digits" data-bind="value: officePhone" >
                </div>
                <div class="form-group dpc">
                    <label >Working Hours</label>
                    <p class="error-div" ></p>
                    <div class="row dayBox EveryDayCheck">
                        <div class="col-sm-4 col-md-3">  
                            <p class="ckBox">
                                <input type="checkbox" data-bind="checked: officeWorkingHours.isEverydayWork, event: {change: $parent.officeWorkingHours.everyDayWorkHour}, attr: {id: 'test2'+officeId()}" />
                                <label data-bind="attr: {for: 'test2'+officeId()}" class="ckColor"> Everyday</label>
                            </p>    
                        </div>
                        <div class="col-sm-4 col-md-3">
                            <div class="date datetime1">
                                <input type="text" class="form-control datetime" placeholder="Opening Hours" data-parsley-required data-parsley-required-message="opening hours required" data-bind="datetimePicker: {optA:'aa'},value: officeWorkingHours.everydayStart, disable: !officeWorkingHours.isEverydayWork(), attr:{'data-parsley-required': officeWorkingHours.isEverydayWork()}">
                                <p class="error-div" ></p>
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-3">
                            <div class="date datetime1">
                                <input type="text" class="form-control datetime" placeholder="Closing Hours" data-parsley-required data-parsley-required-message="closing hours required" data-bind="datetimePicker: {optA:'bb'}, value: officeWorkingHours.everydayEnd, disable: !officeWorkingHours.isEverydayWork(), attr:{'data-parsley-required': officeWorkingHours.isEverydayWork()}">
                            </div>                               
                        </div>
                    </div>
                    <div class="allDay">  
                        <div class="row dayBox">
                            <div class="col-sm-4 col-md-3">  
                                <p class="ckBox">
                                    <input type="checkbox" data-bind="checked: officeWorkingHours.isMondayWork, event: {change: $parent.officeWorkingHours.otherDayWorkHour}, attr: {id: 'mon'+officeId()}"/>
                                    <label data-bind="attr: {for: 'mon'+officeId()}" class="ckColor"> Monday</label>
                                </p>    
                            </div>
                            <div class="col-sm-4 col-md-3">
                                <div class="date datetime1">
                                    <input type="text" class="form-control" placeholder="Opening Hours" data-parsley-required-message="opening hours required" data-bind="datetimePicker: {optA:'aa'},value: officeWorkingHours.mondayStart, disable: !officeWorkingHours.isMondayWork(), attr:{'data-parsley-required': officeWorkingHours.isMondayWork()}">
                                    <p class="error-div" ></p>
                                </div>    
                            </div>
                            <div class="col-sm-4 col-md-3">
                                <div class="date datetime1">
                                    <input type="text" class="form-control" placeholder="Closing Hours" data-parsley-required data-parsley-required-message="closing hours required" data-bind="datetimePicker: {optA:'bb'},value: officeWorkingHours.mondayEnd, disable: !officeWorkingHours.isMondayWork(), attr:{'data-parsley-required': officeWorkingHours.isMondayWork()}" >
                                </div>
                            </div>
                        </div>
                        <div class="row dayBox">
                            <div class="col-sm-4 col-md-3">  
                                <p class="ckBox">
                                    <input type="checkbox" id="tue" data-bind="checked: officeWorkingHours.isTuesdayWork, event: {change: $parent.officeWorkingHours.otherDayWorkHour}, attr: {id: 'tue'+officeId()}" />
                                    <label data-bind="attr: {for: 'tue'+officeId()}" class="ckColor"> Tuesday</label>
                                </p>    
                            </div>
                            <div class="col-sm-4 col-md-3">
                                <div class="date datetime1">
                                    <input type="text" class="form-control" placeholder="Opening Hours" data-parsley-required data-parsley-required-message="opening hours required" data-bind="datetimePicker: {optA:'aa'},value: officeWorkingHours.tuesdayStart, disable: !officeWorkingHours.isTuesdayWork(), attr:{'data-parsley-required': officeWorkingHours.isTuesdayWork()}">
                                    <p class="error-div" ></p>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3">
                                <div class="date datetime1">
                                    <input type="text" class="form-control" placeholder="Closing Hours" data-parsley-required  data-parsley-required-message="closing hours required" data-bind="datetimePicker: {optA:'bb'},value: officeWorkingHours.tuesdayEnd, disable: !officeWorkingHours.isTuesdayWork(), attr:{'data-parsley-required': officeWorkingHours.isTuesdayWork()}" >
                                </div>
                            </div>
                        </div>
                        <div class="row dayBox">
                            <div class="col-sm-4 col-md-3">  
                                <p class="ckBox">
                                    <input type="checkbox" id="wed" data-bind="checked: officeWorkingHours.isWednesdayWork, event: {change: $parent.officeWorkingHours.otherDayWorkHour}, attr: {id: 'wed'+officeId()}"/>
                                    <label data-bind="attr: {for: 'wed'+officeId()}" class="ckColor"> Wednesday</label>
                                </p>    
                            </div>
                            <div class="col-sm-4 col-md-3">
                                <div class="date datetime1">
                                    <input type="text" class="form-control" placeholder="Opening Hours" data-parsley-required data-parsley-required-message="opening hours required" data-bind="datetimePicker: {optA:'aa'},value: officeWorkingHours.wednesdayStart, disable: !officeWorkingHours.isWednesdayWork(), attr:{'data-parsley-required': officeWorkingHours.isWednesdayWork()}">
                                    <p class="error-div"></p>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3">
                                <div class="date datetime1">
                                    <input type="text" class="form-control" placeholder="Closing Hours" data-parsley-required data-parsley-required-message="closing hours required" data-bind="datetimePicker: {optA:'bb'},value: officeWorkingHours.wednesdayEnd, disable: !officeWorkingHours.isWednesdayWork(), attr:{'data-parsley-required': officeWorkingHours.isWednesdayWork()}" >
                                </div>
                            </div>
                        </div>
                        <div class="row dayBox">
                            <div class="col-sm-4 col-md-3">  
                                <p class="ckBox">
                                    <input type="checkbox" id="thu" data-bind="checked: officeWorkingHours.isThursdayWork, event: {change: $parent.officeWorkingHours.otherDayWorkHour}, attr: {id: 'thu'+officeId()}"/>
                                    <label data-bind="attr: {for: 'thu'+officeId()}" class="ckColor"> Thursday</label>
                                </p>    
                            </div>
                            <div class="col-sm-4 col-md-3">
                                <div class="date datetime1">
                                    <input type="text" class="form-control" placeholder="Opening Hours" data-parsley-required data-parsley-required-message="opening hours required " data-bind="datetimePicker: {optA:'aa'},value: officeWorkingHours.thursdayStart, disable: !officeWorkingHours.isThursdayWork(), attr:{'data-parsley-required': officeWorkingHours.isThursdayWork()}">
                                    <p class="error-div"></p>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3">
                                <div class="date datetime1">
                                    <input type="text" class="form-control" placeholder="Closing Hours"data-parsley-required data-parsley-required-message="closing hours required" data-bind="datetimePicker: {optA:'bb'},value: officeWorkingHours.thursdayEnd, disable: !officeWorkingHours.isThursdayWork(), attr:{'data-parsley-required': officeWorkingHours.isThursdayWork()}" >
                                </div>
                            </div>
                        </div>
                        <div class="row dayBox">
                            <div class="col-sm-4 col-md-3">  
                                <p class="ckBox">
                                    <input type="checkbox" id="fri" data-bind="checked: officeWorkingHours.isFridayWork, event: {change: $parent.officeWorkingHours.otherDayWorkHour}, attr: {id: 'fri'+officeId()}"/>
                                    <label data-bind="attr: {for: 'fri'+officeId()}" class="ckColor"> Friday</label>
                                </p>    
                            </div>
                            <div class="col-sm-4 col-md-3">
                                <div class="date datetime1">
                                    <input type="text" class="form-control" placeholder="Opening Hours" data-parsley-required data-parsley-required-message="opening hours required" data-bind="datetimePicker: {optA:'aa'},value: officeWorkingHours.fridayStart, disable: !officeWorkingHours.isFridayWork(), attr:{'data-parsley-required': officeWorkingHours.isFridayWork()}">
                                    <p class="error-div" ></p>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3">
                                <div class="date datetime1">
                                    <input type="text" class="form-control" placeholder="Closing Hours" data-parsley-required data-parsley-required-message="closing hours required" data-bind="datetimePicker: {optA:'bb'},value: officeWorkingHours.fridayEnd, disable: !officeWorkingHours.isFridayWork(), attr:{'data-parsley-required': officeWorkingHours.isFridayWork()}" >
                                </div>
                            </div>
                        </div>
                        <div class="row dayBox">
                            <div class="col-sm-4 col-md-3">  
                                <p class="ckBox">
                                    <input type="checkbox" id="sat" data-bind="checked: officeWorkingHours.isSaturdayWork, event: {change: $parent.officeWorkingHours.otherDayWorkHour}, attr: {id: 'sat'+officeId()}"/>
                                    <label data-bind="attr: {for: 'sat'+officeId()}" class="ckColor"> Saturday</label>
                                </p>    
                            </div>
                            <div class="col-sm-4 col-md-3">
                                <div class="date datetime1">
                                    <input type="text" class="form-control" placeholder="Opening Hours" data-parsley-required data-parsley-required-message="opening hours required" data-bind="datetimePicker: {optA:'aa'},value: officeWorkingHours.saturdayStart, disable: !officeWorkingHours.isSaturdayWork(), attr:{'data-parsley-required': officeWorkingHours.isSaturdayWork()}">
                                    <p class="error-div" ></p>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3">
                                <div class="date datetime1">
                                    <input type="text" class="form-control" placeholder="Closing Hours" data-parsley-required data-parsley-required-message="closing hours required" data-bind="datetimePicker: {optA:'bb'},value: officeWorkingHours.saturdayEnd, disable: !officeWorkingHours.isSaturdayWork(), attr:{'data-parsley-required': officeWorkingHours.isSaturdayWork()}" >
                                </div>
                            </div>
                        </div>
                        <div class="row dayBox">
                            <div class="col-sm-4 col-md-3">  
                                <p class="ckBox">
                                    <input type="checkbox" id="sun" data-bind="checked: officeWorkingHours.isSundayWork, event: {change: $parent.officeWorkingHours.otherDayWorkHour}, attr: {id: 'sun'+officeId()}"/>
                                    <label data-bind="attr: {for: 'sun'+officeId()}" class="ckColor"> Sunday</label>
                                </p>    
                            </div>
                            <div class="col-sm-4 col-md-3">
                                <div class="date datetime1">
                                    <input type="text" class="form-control" placeholder="Opening Hours" data-parsley-required data-parsley-required-message="opening hours required" data-bind="datetimePicker: {optA:'aa'},value: officeWorkingHours.sundayStart, disable: !officeWorkingHours.isSundayWork(), attr:{'data-parsley-required': officeWorkingHours.isSundayWork()}">
                                    <p class="error-div" ></p>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3">
                                <div class="date datetime1">
                                    <input type="text" class="form-control" placeholder="Closing Hours" data-parsley-required data-parsley-required-message="closing hours required" data-bind="datetimePicker: {optA:'bb'},value: officeWorkingHours.sundayEnd, disable: !officeWorkingHours.isSundayWork(), attr:{'data-parsley-required': officeWorkingHours.isSundayWork()}" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>	
                <div class="form-group">
                    <label>Office Location Information <i class="optional">(Optional)</i></label>
                    <textarea class="form-control txtHeight" data-parsley-maxlength="500" data-parsley-maxlength-message="Charcter should be 500" data-bind="value: officeInfo" ></textarea>
                </div>
        </script>
    </div>
</div>    
@section('js')
<script src="{{asset('web/scripts/multiple-select.js')}}"></script>
<script>
    var placeSearch, autocomplete;
    var componentForm = {
        postal_code: 'short_name'
    };

    function initAutocomplete() {
        // Create the autocomplete object, restricting the search to geographical
        // location types.
        console.log(document.getElementById('autocomplete'));
        autocomplete = new google.maps.places.SearchBox(
                /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
                {types: ['geocode']});

        // When the user selects an address from the dropdown, populate the address
        // fields in the form.
        autocomplete.addListener('places_changed', fillInAddress);
    }

    function fillInAddress() {
        // Get the place details from the autocomplete object.
        var allPlace = autocomplete.getPlaces();

        allPlace.forEach(function (place) {
            $('#postal_code').val('');
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
            $('#autocomplete').val(place.formatted_address);
            checkLocation($('#postal_code').val(), '');

        });

    }

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCsIYaIMo9hd5yEL7pChkVPKPWGX6rFcv8&libraries=places"
async defer></script>
<script src="{{asset('web/scripts/edit-profile.js')}}"></script>
@endsection
@endsection
