@extends('web.layouts.dashboard')

@section('css')
<link rel="stylesheet" href="{{asset('web/css/multiple-select.css')}}">
<link rel="stylesheet" href="{{asset('web/css/style.css')}}">

@endsection
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container padding-container-template" id="edit-job">
    <!--breadcrumb-->
    <ul class="breadcrumb ">
        <li><a href="{{ url('job/lists') }}">Jobs Listing</a></li>
        <li><a href="{{ url('job/details/'.$jobId) }}">Jobs Detail</a></li>
        <li class="active">Edit Jobs Detail</li>
    </ul>
    <!--/breadcrumb-->

    <form  data-bind="submit: publishJob">
        <div class="row sec-mob">
            <div class="col-sm-6 mr-b-10 col-xs-6">
                <div class="section-title">Edit Job Opening</div>
            </div>
            <div class="col-sm-6 text-right mr-b-10 col-xs-6" data-bind="visible: showEdit">
                <button type="button" class="btn-link mr-r-5" data-bind="click: $root.cancelJob">Cancel</button>
                <button type="button" class="btn pd-l-25 pd-r-25 btn-primary-outline" data-bind="click: $root.deleteJob">Delete</button>
                <button type="submit" class="btn btn-primary pd-l-25 pd-r-25">Publish</button>
            </div>
            <div class="col-sm-6 text-right mr-b-10 col-xs-6" data-bind="visible: cannotEdit">
                <p class="pull-right">Cannot edit this job</p>
            </div>
        </div>

        <div class="commonBox cboxbottom ">

            <div class="form-group custom-select">
                <input hidden value="{{$jobId}}" id="jobIdValue">
                <label >Dental Office Address</label>
                <select class="selectpicker" id="select-office-address" data-bind="options: allLocations, optionsText: 'address', optionsValue: 'address', selectedOptions: defaultSelectLocation, event: {change: showOfficeDetails}">
                </select>
            </div>
            <div class="form-group custom-select">
                <label >Preferred Job Locations</label>
                <select class="selectpicker" id="select-preferred-location" data-bind="options: preferredJobLocations, optionsText: 'preferred_location_name', optionsValue: 'id', selectedOptions: defaultSelectPreferredJobLocation">
                </select>
            </div>
            <div class="form-group">
                <label  >Job Type</label>
                <div class="row">
                    <div class="col-md-4 col-lg-4">
                        <div class="full-time-box">
                            <input class="magic-radio" type="radio" name="radio" id="fulltime" value="Full Time" data-bind="checked: jobType">
                            <label for="Full Time" data-bind="click: $root.selecteJobType">
                                Full Time
                            </label>
                        </div>  
                    </div>
                    <div class="col-md-4  col-lg-4 ">
                        <div class="full-time-box">
                            <input class="magic-radio" type="radio" name="radio" id="parttime" value="Part Time" data-bind="checked: jobType">
                            <label for="Part Time" data-bind="click: $root.selecteJobType">
                                Part Time
                            </label>
                            <select multiple="multiple" id="monthSelect" style="display: none;" class="select-days-custom" data-bind="options: allPartTimeDays, selectedOptions: partTimeDays">
                            </select>
                        </div>  

                    </div>
                    <div class="col-md-4 col-lg-4 ">
                        <div class="full-time-box">
                            <input class="magic-radio" type="radio" name="radio" id="temporary" value="Temporary" data-bind="checked: jobType">
                            <label for="Temporary" data-bind="click: $root.selecteJobType">
                                Temporary
                            </label>
                            <input type="text" name="tempJobDates" id="CoverStartDateOtherPicker" class="date-instance" />
                            <p class="error-div" data-bind="text: temporaryJobError"></p>
                        </div>  
                    </div>
                </div>

            </div>
            <div class="form-group custom-select job-opening hide">
                <label >Number of Candidates Needed</label>
                <input name="noOfJobs" type="text" min="1" id="jobopening" class="form-control" data-parsley-required-message="Total job openings required" data-bind="visible: showTotalJobOpenings,value: totalJobOpening, attr:{'data-parsley-required': showTotalJobOpenings}" />
            </div>

        </div>

        <div class="profile-div mr-t-30">
            <!--ko foreach: selectedOffice-->
            <div class="commonBox cboxbottom masterBox">
                <div class="form-group">
                    <div class="detailTitleBlock">
                        <h5>OFFICE DETAILS</h5>
                    </div>
                    <label >Office Type</label>
                    <div class="slt">
                        <select class="ddlCars" multiple="true" data-bind=" options: $parent.allOfficeTypes, selectedOptions: selectedOfficeType ">
                        </select>
                        <p class="error-div" data-bind="text: $root.officeTypeError"></p>
                    </div>
                </div>
                <div class="form-group">
                    <label>Dental Office Address</label>
                    <input type="text" value="" data-bind="value: selectedOfficeAddress, event: {focus: $root.getOfficeName}" id="officeAddress" name="officeName" class="form-control txtBtnDisable"  data-parsley-required data-parsley-required-message="Required">
                    <p class="error-div" data-bind="text: $root.locationError"></p>
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" id="editPhoneNumber" class="form-control" data-parsley-required data-parsley-required-message="phone number required" data-parsley-maxlength="10" data-parsley-maxlength-message="number should be 10" data-parsley-trigger="keyup" data-parsley-type="digits"  data-bind="value: selectedOfficePhone">
                    <p class="error-div" data-bind="text: $root.phoneNumberError"></p>
                </div>
                <div class="form-group dpc">
                    <label >Working Hours</label>
                    <p class="error-div" data-bind="text: $root.mixedWorkHourError"></p>
                    <div class="row dayBox EveryDayCheck">
                        <div class="col-sm-4 col-md-3">  
                            <p class="ckBox">
                                <input type="checkbox" id="test2" data-bind="checked: selectedOfficeWorkingHours.isEverydayWork, event: {change: $root.everyDayWorkHour}" />
                                <label for="test2" class="ckColor"> Everyday</label>
                            </p>    
                        </div>
                        <div class="col-sm-4 col-md-3">
                            <div class="date datetime1">
                                <input type="text" id="everydayStart" class="form-control datetime" placeholder="Opening Hours" data-parsley-required data-parsley-required-message="opening hours required" data-bind="datetimePicker: {optA:'aa'},value: selectedOfficeWorkingHours.everydayStart, disable: !selectedOfficeWorkingHours.isEverydayWork(), attr:{'data-parsley-required': selectedOfficeWorkingHours.isEverydayWork()}">
                                <p class="error-div" data-bind="text: $root.everydayTimeError"></p>
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-3">
                            <div class="date datetime1">
                                <input type="text" id="everydayEnd" class="form-control datetime" placeholder="Closing Hours" data-parsley-required data-parsley-required-message="closing hours required" data-bind="datetimePicker: {optA:'bb'}, value: selectedOfficeWorkingHours.everydayEnd, disable: !selectedOfficeWorkingHours.isEverydayWork(), attr:{'data-parsley-required': selectedOfficeWorkingHours.isEverydayWork()}" >
                            </div>                               
                        </div>
                    </div>
                    <div class="allDay">  
                        <div class="row dayBox">
                            <div class="col-sm-4 col-md-3">  
                                <p class="ckBox">
                                    <input type="checkbox" id="mon" data-bind="checked: selectedOfficeWorkingHours.isMondayWork, event: {change: $root.otherDayWorkHour}" />
                                    <label for="mon" class="ckColor"> Monday</label>
                                </p>    
                            </div>
                            <div class="col-sm-4 col-md-3">
                                <div class="date datetime1">
                                    <input type="text" class="form-control" placeholder="Opening Hours" data-parsley-required-message="opening hours required" data-bind="datetimePicker: {optA:'aa'},value: selectedOfficeWorkingHours.mondayStart, disable: !selectedOfficeWorkingHours.isMondayWork(), attr:{'data-parsley-required': selectedOfficeWorkingHours.isMondayWork()}">
                                    <p class="error-div" data-bind="text: $root.mondayTimeError"></p>
                                </div>    
                            </div>
                            <div class="col-sm-4 col-md-3">
                                <div class="date datetime1">
                                    <input type="text" class="form-control" placeholder="Closing Hours" data-parsley-required data-parsley-required-message="closing hours required" data-bind="datetimePicker: {optA:'bb'},value: selectedOfficeWorkingHours.mondayEnd, disable: !selectedOfficeWorkingHours.isMondayWork(), attr:{'data-parsley-required': selectedOfficeWorkingHours.isMondayWork()}" >
                                </div>
                            </div>
                        </div>
                        <div class="row dayBox">
                            <div class="col-sm-4 col-md-3">  
                                <p class="ckBox">
                                    <input type="checkbox" id="tue" data-bind="checked: selectedOfficeWorkingHours.isTuesdayWork, event: {change: $root.otherDayWorkHour}" />
                                    <label for="tue" class="ckColor"> Tuesday</label>
                                </p>    
                            </div>
                            <div class="col-sm-4 col-md-3">
                                <div class="date datetime1">
                                    <input type="text" class="form-control" placeholder="Opening Hours" data-parsley-required data-parsley-required-message="opening hours required" data-bind="datetimePicker: {optA:'aa'},value: selectedOfficeWorkingHours.tuesdayStart, disable: !selectedOfficeWorkingHours.isTuesdayWork(), attr:{'data-parsley-required': selectedOfficeWorkingHours.isTuesdayWork()}">
                                    <p class="error-div" data-bind="text: $root.tuesdayTimeError"></p>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3">
                                <div class="date datetime1">
                                    <input type="text" class="form-control" placeholder="Closing Hours" data-parsley-required  data-parsley-required-message="closing hours required" data-bind="datetimePicker: {optA:'bb'},value: selectedOfficeWorkingHours.tuesdayEnd, disable: !selectedOfficeWorkingHours.isTuesdayWork(), attr:{'data-parsley-required': selectedOfficeWorkingHours.isTuesdayWork()}" >
                                </div>
                            </div>
                        </div>
                        <div class="row dayBox">
                            <div class="col-sm-4 col-md-3">  
                                <p class="ckBox">
                                    <input type="checkbox" id="wed" data-bind="checked: selectedOfficeWorkingHours.isWednesdayWork, event: {change: $root.otherDayWorkHour}" />
                                    <label for="wed" class="ckColor"> Wednesday</label>
                                </p>    
                            </div>
                            <div class="col-sm-4 col-md-3">
                                <div class="date datetime1">
                                    <input type="text" class="form-control" placeholder="Opening Hours" data-parsley-required data-parsley-required-message="opening hours required" data-bind="datetimePicker: {optA:'aa'},value: selectedOfficeWorkingHours.wednesdayStart, disable: !selectedOfficeWorkingHours.isWednesdayWork(), attr:{'data-parsley-required': selectedOfficeWorkingHours.isWednesdayWork()}">
                                    <p class="error-div" data-bind="text: $root.wednesdayTimeError"></p>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3">
                                <div class="date datetime1">
                                    <input type="text" class="form-control" placeholder="Closing Hours" data-parsley-required data-parsley-required-message="closing hours required" data-bind="datetimePicker: {optA:'bb'},value: selectedOfficeWorkingHours.wednesdayEnd, disable: !selectedOfficeWorkingHours.isWednesdayWork(), attr:{'data-parsley-required': selectedOfficeWorkingHours.isWednesdayWork()}">
                                </div>
                            </div>
                        </div>
                        <div class="row dayBox">
                            <div class="col-sm-4 col-md-3">  
                                <p class="ckBox">
                                    <input type="checkbox" id="thu" data-bind="checked: selectedOfficeWorkingHours.isThursdayWork, event: {change: $root.otherDayWorkHour}" />
                                    <label for="thu" class="ckColor"> Thursday</label>
                                </p>    
                            </div>
                            <div class="col-sm-4 col-md-3">
                                <div class="date datetime1">
                                    <input type="text" class="form-control" placeholder="Opening Hours" data-parsley-required data-parsley-required-message="opening hours required" data-bind="datetimePicker: {optA:'aa'},value: selectedOfficeWorkingHours.thursdayStart, disable: !selectedOfficeWorkingHours.isThursdayWork(), attr:{'data-parsley-required': selectedOfficeWorkingHours.isThursdayWork()}">
                                    <p class="error-div" data-bind="text: $root.thursdayTimeError"></p>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3">
                                <div class="date datetime1">
                                    <input type="text" class="form-control" placeholder="Closing Hours"data-parsley-required data-parsley-required-message="closing hours required" data-bind="datetimePicker: {optA:'bb'},value: selectedOfficeWorkingHours.thursdayEnd, disable: !selectedOfficeWorkingHours.isThursdayWork(), attr:{'data-parsley-required': selectedOfficeWorkingHours.isThursdayWork()}" >
                                </div>
                            </div>
                        </div>
                        <div class="row dayBox">
                            <div class="col-sm-4 col-md-3">  
                                <p class="ckBox">
                                    <input type="checkbox" id="fri" data-bind="checked: selectedOfficeWorkingHours.isFridayWork, event: {change: $root.otherDayWorkHour}" />
                                    <label for="fri" class="ckColor"> Friday</label>
                                </p>    
                            </div>
                            <div class="col-sm-4 col-md-3">
                                <div class="date datetime1">
                                    <input type="text" class="form-control" placeholder="Opening Hours" data-parsley-required data-parsley-required-message="opening hours required" data-bind="datetimePicker: {optA:'aa'},value: selectedOfficeWorkingHours.fridayStart, disable: !selectedOfficeWorkingHours.isFridayWork(), attr:{'data-parsley-required': selectedOfficeWorkingHours.isFridayWork()}">
                                    <p class="error-div" data-bind="text: $root.fridayTimeError"></p>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3">
                                <div class="date datetime1">
                                    <input type="text" class="form-control" placeholder="Closing Hours" data-parsley-required data-parsley-required-message="closing hours required" data-bind="datetimePicker: {optA:'bb'},value: selectedOfficeWorkingHours.fridayEnd, disable: !selectedOfficeWorkingHours.isFridayWork(), attr:{'data-parsley-required': selectedOfficeWorkingHours.isFridayWork()}" >
                                </div>
                            </div>
                        </div>
                        <div class="row dayBox">
                            <div class="col-sm-4 col-md-3">  
                                <p class="ckBox">
                                    <input type="checkbox" id="sat" data-bind="checked: selectedOfficeWorkingHours.isSaturdayWork, event: {change: $root.otherDayWorkHour}" />
                                    <label for="sat" class="ckColor"> Saturday</label>
                                </p>    
                            </div>
                            <div class="col-sm-4 col-md-3">
                                <div class="date datetime1">
                                    <input type="text" class="form-control" placeholder="Opening Hours" data-parsley-required data-parsley-required-message="opening hours required" data-bind="datetimePicker: {optA:'aa'},value: selectedOfficeWorkingHours.saturdayStart, disable: !selectedOfficeWorkingHours.isSaturdayWork(), attr:{'data-parsley-required': selectedOfficeWorkingHours.isSaturdayWork()}">
                                    <p class="error-div" data-bind="text: $root.saturdayTimeError"></p>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3">
                                <div class="date datetime1">
                                    <input type="text" class="form-control" placeholder="Closing Hours" data-parsley-required data-parsley-required-message="closing hours required" data-bind="datetimePicker: {optA:'bb'},value: selectedOfficeWorkingHours.saturdayEnd, disable: !selectedOfficeWorkingHours.isSaturdayWork(), attr:{'data-parsley-required': selectedOfficeWorkingHours.isSaturdayWork()}" >
                                </div>
                            </div>
                        </div>
                        <div class="row dayBox">
                            <div class="col-sm-4 col-md-3">  
                                <p class="ckBox">
                                    <input type="checkbox" id="sun" data-bind="checked: selectedOfficeWorkingHours.isSundayWork, event: {change: $root.otherDayWorkHour}" />
                                    <label for="sun" class="ckColor"> Sunday</label>
                                </p>    
                            </div>
                            <div class="col-sm-4 col-md-3">
                                <div class="date datetime1">
                                    <input type="text" class="form-control" placeholder="Opening Hours" data-parsley-required data-parsley-required-message="opening hours required" data-bind="datetimePicker: {optA:'aa'},value: selectedOfficeWorkingHours.sundayStart, disable: !selectedOfficeWorkingHours.isSundayWork(), attr:{'data-parsley-required': selectedOfficeWorkingHours.isSundayWork()}">
                                    <p class="error-div" data-bind="text: $root.sundayTimeError"></p>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3">
                                <div class="date datetime1">
                                    <input type="text" class="form-control" placeholder="Closing Hours" data-parsley-required data-parsley-required-message="closing hours required" data-bind="datetimePicker: {optA:'bb'},value: selectedOfficeWorkingHours.sundayEnd, disable: !selectedOfficeWorkingHours.isSundayWork(), attr:{'data-parsley-required': selectedOfficeWorkingHours.isSundayWork()}" >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>	
                <div class="form-group">
                    <label>Additional Helpful Information <i class="optional">(Optional)</i></label>
                    <label>example:where to park, lunch hour, what to wear, etc.</label>
                    <textarea class="form-control txtHeight" id="optionalInfo" data-parsley-maxlength="500" data-parsley-maxlength-message="Charcter should be 500" data-bind="value: selectedOfficeInfo"></textarea>
                </div>	
            </div>
            <!--/ko-->
        </div>
    </form>
    <div id="actionModal" class="modal fade" role="dialog">
        <div class="modal-dialog custom-modal modal-sm">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" data-bind="visible:cancelButtonDelete">&times;</button>
                    <h4 class="modal-title" data-bind="text:headMessage"></h4>
                </div>
                <div class="modal-body">
                    <p class="text-center" data-bind="text:prompt"></p>
                    <div class="mr-t-20 mr-b-30 dev-pd-l-13p" data-bind="visible: showModalFooter">
                        <button type="button" class="btn btn-link mr-r-5" data-dismiss="modal">Close</button>
                        <button type="submit" id="actionButton" class="btn btn-primary pd-l-30 pd-r-30">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCsIYaIMo9hd5yEL7pChkVPKPWGX6rFcv8&libraries=places" async defer></script>
<script src="{{asset('web/scripts/multiple-select.js')}}"></script>

<script>

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script src="{{asset('web/scripts/edit-job.js')}}"></script>
@endsection