@extends('web.layouts.dashboard')

@section('css')
<link rel="stylesheet" href="{{asset('web/css/style.css')}}">

@endsection
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container padding-container-template" id="edit-job">
    <!--breadcrumb-->
    <ul class="breadcrumb ">
        <li><a href="#">Jobs Listing</a></li>
        <li><a href="#">Jobs Detail</a></li>
        <li class="active">Edit Jobs Detail</li>
    </ul>
    <!--/breadcrumb-->

    <form data-parsley-validate>
        <div class="row sec-mob">
            <div class="col-sm-6 mr-b-10 col-xs-6">
                <div class="section-title">Edit Job</div>
            </div>
            <div class="col-sm-6 text-right mr-b-10 col-xs-6" data-bind="visible: showEdit">
                <button type="button" class="btn-link mr-r-5">Cancel</button>

                <button type="submit" data-bind="click: $root.publishJob" class="btn btn-primary pd-l-25 pd-r-25">Publish</button>
            </div>
            <div class="col-sm-6 text-right mr-b-10 col-xs-6" data-bind="visible: cannotEdit">
                <p class="pull-right">Cannot edit this job</p>
            </div>
        </div>

        <div class="commonBox cboxbottom padding-dentaltemplate">

            <div class="form-group custom-select">
                <label >Dental Office Address</label>
                <select data-bind="options: allLocations, optionsText: 'address', optionsValue: 'id', selectedOptions: location, event:{ change: $root.showOfficeDetails}">
                </select>
<!--                <select  id="officeAddress" class="selectpicker" data-bind="options: selectedLocations, selectedOptions: location">
                </select>-->

                <!--<p class="error-div">Job cannot be currently created for this location. We will soon be available in your area.</p>-->

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
                            <input type="text" id="CoverStartDateOtherPicker" class="date-instance" />
                        </div>  
                    </div>
                </div>

            </div>
            <div class="form-group custom-select job-opening hide">
                <label >Total Job Opening</label>
                <input name="noOfJobs" type="text" id="jobopening" class="form-control" data-bind="value: totalJobOpening" />
            </div>

        </div>

        <div class="profile-div mr-t-30">
            <!--ko foreach: selectedOffice-->
            <div class="commonBox cboxbottom masterBox">
                <div class="form-group">
                    <div class="detailTitleBlock">
                        <h5>OFFICE DETAILS</h5>
                    </div>
                    <label >Dental Office Type</label>
                    <div class="slt">
                        <select class="ddlCars" multiple="true" data-bind=" options: $parent.allOfficeTypes, selectedOptions: selectedOfficeType ">
                        </select>
                        <!--<p class="error-div">Job cannot be currently created for this location. We will soon be available in your area.</p>-->
                    </div>
                </div>
                <div class="form-group">
                    <label>Dental Office Address</label>
                    <input type="text" value="" data-bind="click: $root.getOfficeName, value: selectedOfficeAddress" id="officeName" name="officeName" class="form-control txtBtnDisable"  data-parsley-required data-parsley-required-message="Required">
                    <!--<input type="text" class="form-control"  placeholder="Office name, Street, City, Zip Code and Country" data-parsley-required data-parsley-required-message="office address required" data-bind="value: selectedOfficeAddress">-->
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" class="form-control" data-parsley-required data-parsley-required-message="phone number required" data-parsley-maxlength="10" data-parsley-maxlength-message="number should be 10" data-parsley-trigger="keyup" data-parsley-type="digits"  data-bind="value: selectedOfficePhone">
                </div>
                <div class="form-group" data-bind="datetimePicker: datePickerBinding">
                    <label >Working Hours</label>
                    <div class="row dayBox EveryDayCheck">
                        <div class="col-sm-4 col-md-3">  
                            <p class="ckBox">
                                <input type="checkbox" id="test2" data-bind="checked: selectedOfficeWorkingHours.isEverydayWork, event: {change: $root.everyDayWorkHour}" />
                                <label for="test2" class="ckColor"> Everyday</label>
                            </p>    
                        </div>
                        <div class="col-sm-4 col-md-3">
                            <div class="date datetime1">
                                <input type="text" class="form-control datetime" placeholder="Opening Hours" data-parsley-required data-parsley-required-message="opening hours required" data-bind="value: selectedOfficeWorkingHours.everydayStart, disable: !selectedOfficeWorkingHours.isEverydayWork()">
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-3">
                            <div class="date datetime1">
                                <input type="text" class="form-control datetime" placeholder="Closing Hours" data-parsley-required data-parsley-required-message="closing hours required" data-bind="value: selectedOfficeWorkingHours.everydayEnd, disable: !selectedOfficeWorkingHours.isEverydayWork()" >
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
                                    <input type="text" class="form-control" placeholder="Opening Hours" data-parsley-required data-parsley-required-message="opening hours required" data-bind="value: selectedOfficeWorkingHours.mondayStart, disable: !selectedOfficeWorkingHours.isMondayWork(), click: $root.datePicker1">
                                </div>    
                            </div>
                            <div class="col-sm-4 col-md-3">
                                <div class="date datetime1">
                                    <input type="text" class="form-control" placeholder="Closing Hours" data-parsley-required data-parsley-required-message="closing hours required" data-bind="value: selectedOfficeWorkingHours.mondayEnd, disable: !selectedOfficeWorkingHours.isMondayWork(), click: $root.datePicker2" >
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
                                <input type="text" class="form-control" placeholder="Opening Hours" data-parsley-required data-parsley-required-message="opening hours required" data-bind="value: selectedOfficeWorkingHours.tuesdayStart, disable: !selectedOfficeWorkingHours.isTuesdayWork()">
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3">
                                <div class="date datetime1">
                                    <input type="text" class="form-control" placeholder="Closing Hours" data-parsley-required  data-parsley-required-message="closing hours required" data-bind="value: selectedOfficeWorkingHours.tuesdayEnd, disable: !selectedOfficeWorkingHours.isTuesdayWork()" >
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
                                    <input type="text" class="form-control" placeholder="Opening Hours" data-parsley-required data-parsley-required-message="opening hours required" data-bind="value: selectedOfficeWorkingHours.wednesdayStart, disable: !selectedOfficeWorkingHours.isWednesdayWork()">
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3">
                                <div class="date datetime1">
                                    <input type="text" class="form-control" placeholder="Closing Hours" data-parsley-required data-parsley-required-message="closing hours required" data-bind="value: selectedOfficeWorkingHours.wednesdayEnd, disable: !selectedOfficeWorkingHours.isWednesdayWork()" >
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
                                    <input type="text" class="form-control" placeholder="Opening Hours" data-parsley-required data-parsley-required-message="opening hours required" data-bind="value: selectedOfficeWorkingHours.thursdayStart, disable: !selectedOfficeWorkingHours.isThursdayWork()">
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3">
                                <div class="date datetime1">
                                    <input type="text" class="form-control" placeholder="Closing Hours"data-parsley-required data-parsley-required-message="closing hours required" data-bind="value: selectedOfficeWorkingHours.thursdayEnd, disable: !selectedOfficeWorkingHours.isThursdayWork()" >
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
                                    <input type="text" class="form-control" placeholder="Opening Hours" data-parsley-required data-parsley-required-message="opening hours required" data-bind="value: selectedOfficeWorkingHours.fridayStart, disable: !selectedOfficeWorkingHours.isFridayWork()">
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3">
                                <div class="date datetime1">
                                    <input type="text" class="form-control" placeholder="Closing Hours" data-parsley-required data-parsley-required-message="closing hours required" data-bind="value: selectedOfficeWorkingHours.fridayEnd, disable: !selectedOfficeWorkingHours.isFridayWork()" >
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
                                    <input type="text" class="form-control" placeholder="Opening Hours" data-parsley-required data-parsley-required-message="opening hours required" data-bind="value: selectedOfficeWorkingHours.saturdayStart, disable: !selectedOfficeWorkingHours.isSaturdayWork()">
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3">
                                <div class="date datetime1">
                                    <input type="text" class="form-control" placeholder="Closing Hours" data-parsley-required data-parsley-required-message="closing hours required" data-bind="value: selectedOfficeWorkingHours.saturdayEnd, disable: !selectedOfficeWorkingHours.isSaturdayWork()" >
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
                                    <input type="text" class="form-control" placeholder="Opening Hours" data-parsley-required data-parsley-required-message="opening hours required" data-bind="value: selectedOfficeWorkingHours.sundayStart, disable: !selectedOfficeWorkingHours.isSundayWork()">
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-3">
                                <div class="date datetime1">
                                    <input type="text" class="form-control" placeholder="Closing Hours" data-parsley-required data-parsley-required-message="closing hours required" data-bind="value: selectedOfficeWorkingHours.sundayEnd, disable: !selectedOfficeWorkingHours.isSundayWork()" >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>	
                <div class="form-group">
                    <label>Office Location Information <i class="optional">(Optional)</i></label>
                    <textarea class="form-control txtHeight"  data-parsley-required data-parsley-required-message="location information required"  data-parsley-maxlength="100" data-parsley-maxlength-message="Charcter should be 500" data-bind="text: selectedOfficeInfo"></textarea>
                </div>	
            </div>
            <!--/ko-->
        </div>
    </form>
</div>

@endsection

@section('js')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCsIYaIMo9hd5yEL7pChkVPKPWGX6rFcv8&libraries=places" async defer></script>
<script src="{{asset('web/scripts/multiple-select.js')}}"></script>
<script>



</script>
<script>

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

//$(function () {

    var OfficeModel = function (data) {
        var me = this;
        me.selectedOfficeId = ko.observable();
        me.selectedOfficeAddress = ko.observable('');
        me.selectedOfficePhone = ko.observable();
        me.selectedOfficeInfo = ko.observable('');
        me.selectedOfficeWorkingHours = ko.observable();
        me.selectedOfficeZipcode = ko.observable();
        me.datePickerBinding = ko.observable(false);
        me.selectedOfficeType = ko.observableArray([]);

        me._init = function (d) {
            me.selectedOfficeId(d.id);
            splitOfficeType = d.office_type_name.split(',');
            for (i in splitOfficeType) {
                me.selectedOfficeType.push(splitOfficeType[i]);
            }
            me.selectedOfficeAddress(d.address);
            me.selectedOfficePhone(d.phone_no);
            me.selectedOfficeInfo(d.office_location);
            me.selectedOfficeZipcode(d.zipcode);
            me.selectedOfficeWorkingHours = new WorkingHourModel(d);
            me.datePickerBinding(true);
        };

        me._init(data);
    };

//    var DayVM = function(){
//        var me = this;
//        me.label = ko.observable()
//        me.isSelected = ko.observable();
//        me.startTime = ko.observable();
//        me.endTime = ko.observable();
//    }
    var WorkingHourModel = function (data) {
        var me = this;
        me.isMondayWork = ko.observable(false);
        me.mondayStart = ko.observable(null);
        me.mondayEnd = ko.observable(null);
        me.isTuesdayWork = ko.observable(false);
        me.tuesdayStart = ko.observable(null);
        me.tuesdayEnd = ko.observable(null);
        me.isWednesdayWork = ko.observable(false);
        me.wednesdayStart = ko.observable(null);
        me.wednesdayStart.subscribe(function(){
            alert(11);
        });
        me.wednesdayEnd = ko.observable(null);
        me.isThursdayWork = ko.observable(false);
        me.thursdayStart = ko.observable(null);
        me.thursdayEnd = ko.observable(null);
        me.isFridayWork = ko.observable(false);
        me.fridayStart = ko.observable(null);
        me.fridayEnd = ko.observable(null);
        me.isSaturdayWork = ko.observable(false);
        me.saturdayStart = ko.observable(null);
        me.saturdayEnd = ko.observable(null);
        me.isSundayWork = ko.observable(false);
        me.sundayStart = ko.observable(null);
        me.sundayEnd = ko.observable(null);
        me.isEverydayWork = ko.observable(false);
        me.everydayStart = ko.observable(null);
        me.everydayEnd = ko.observable(null);

        me._init = function (d) {
            if (d.work_everyday_start == "00:00:00" && d.work_everyday_end == "00:00:00") {
                if (d.monday_start != "00:00:00") {
                    me.isMondayWork(true);
                    dates = me.getDateFunc(d.monday_start, d.monday_end);
                    me.mondayStart(moment(dates[0]).format('LT'));
                    me.mondayEnd(moment(dates[1]).format('LT'));
                }
                if (d.tuesday_start != "00:00:00") {
                    me.isTuesdayWork(true);
                    dates = me.getDateFunc(d.tuesday_start, d.tuesday_end);
                    me.tuesdayStart(moment(dates[0]).format('LT'));
                    me.tuesdayEnd(moment(dates[1]).format('LT'));
                }
                if (d.wednesday_start != "00:00:00") {
                    me.isWednesdayWork(true);
                    dates = me.getDateFunc(d.wednesday_start, d.wednesday_end);
                    me.wednesdayStart(moment(dates[0]).format('LT'));
                    me.wednesdayEnd(moment(dates[1]).format('LT'));
                }
                if (d.thursday_start != "00:00:00") {
                    me.isThursdayWork(true);
                    dates = me.getDateFunc(d.thursday_start, d.thursday_end);
                    me.thursdayStart(moment(dates[0]).format('LT'));
                    me.thursdayEnd(moment(dates[1]).format('LT'));
                }
                if (d.friday_start != "00:00:00") {
                    me.isFridayWork(true);
                    dates = me.getDateFunc(d.friday_start, d.friday_end);
                    me.fridayStart(moment(dates[0]).format('LT'));
                    me.fridayEnd(moment(dates[1]).format('LT'));
                }
                if (d.saturday_start != "00:00:00") {
                    me.isSaturdayWork(true);
                    dates = me.getDateFunc(d.saturday_start, d.saturday_end);
                    me.saturdayStart(moment(dates[0]).format('LT'));
                    me.saturdayEnd(moment(dates[1]).format('LT'));
                }
                if (d.sunday_start != "00:00:00") {
                    me.isSundayWork(true);
                    dates = me.getDateFunc(d.sunday_start, d.sunday_end);
                    me.sundayStart(moment(dates[0]).format('LT'));
                    me.sundayEnd(moment(dates[1]).format('LT'));
                }
            } else {
                me.isEverydayWork(true);
                dates = me.getDateFunc(d.work_everyday_start, d.work_everyday_end);
                me.everydayStart(moment(dates[0]).format('LT'));
                me.everydayEnd(moment(dates[1]).format('LT'));
            }
        };

        me.getDateFunc = function (start, end) {
            splited1 = start.split(':');
            splited2 = end.split(':');
            date1 = new Date('', '', '', splited1[0], splited1[1]);
            date2 = new Date('', '', '', splited2[0], splited2[1]);
            return [date1, date2];
        };

        me._init(data);
    };

    ko.bindingHandlers.datetimePicker = {
        init: function (element, valueAccessor) {
            $('.datetime1').datetimepicker({
                format: 'hh:mm A',
                'allowInputToggle': true,
                minDate: moment().startOf('day'),
                maxDate: moment().endOf('day')
            });
        }
    };

    var EditJobVM = function () {
        var me = this;
        me.showEdit = ko.observable(true);
        me.cannotEdit = ko.observable(false);
        me.allLocations = ko.observableArray([]);
        me.selectedLocations = ko.observableArray([]);
        me.location = ko.observableArray([]);
        me.jobType = ko.observable('');
        me.totalJobOpening = ko.observable();
        me.jobOfficeId = ko.observable();
        me.showTotalJobOpenings = ko.observable(false);
        me.selectedOffice = ko.observableArray([]);
        me.allOfficeTypes = ko.observableArray([]);
        me.selectedJobType = ko.observable('');
        me.allPartTimeDays = ko.observableArray([]);
        me.partTimeDays = ko.observableArray([]);

        var placeSearch, autocomplete, autocomplete1, autocomplete2, officeName;
        var componentForm = {
            postal_code: 'short_name'
        };

        var autocomplete = {};
        var autocompletesWraps = ['autocomplete', 'autocomplete1', 'autocomplete2'];


        me.getJobDetails = function () {
            jobId = <?php echo json_encode($jobId) ?>;
            $.get('/job/edit-details', {jobId: jobId}, function (d) {
                console.log(d);
                if (d.jobSeekerStatus != 0) {
                    me.cannotEdit(true);
                    me.showEdit(false);
                } else {
                    me.cannotEdit(false);
                    me.showEdit(true);
                }
                me.location.push(d.address);
                me.allPartTimeDays(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']);
                if (d.jobDetails.job_type == 1) {
                    me.jobType("Full Time");
                    me.selectedJobType("Full Time");
                } else if (d.jobDetails.job_type == 2) {
                    me.selectedJobType("Part Time");
                    me.jobType("Part Time");
                    
                    if (d.jobDetails.is_monday !== null && d.jobDetails.is_monday !== 0) {
                        me.partTimeDays.push("Monday");
                    }
                    if (d.jobDetails.is_tuesday !== null && d.jobDetails.is_tuesday !== 0) {
                        me.partTimeDays.push("Tuesday");
                    }
                    if (d.jobDetails.is_wednesday !== null && d.jobDetails.is_wednesday !== 0) {
                        me.partTimeDays.push("Wednesday");
                    }
                    if (d.jobDetails.is_thursday !== null && d.jobDetails.is_thursday !== 0) {
                        me.partTimeDays.push("Thursday");
                    }
                    if (d.jobDetails.is_friday !== null && d.jobDetails.is_friday !== 0) {
                        me.partTimeDays.push("Friday");
                    }
                    if (d.jobDetails.is_saturday !== null && d.jobDetails.is_saturday !== 0) {
                        me.partTimeDays.push("Saturday");
                    }
                    if (d.jobDetails.is_sunday !== null && d.jobDetails.is_sunday !== 0) {
                        me.partTimeDays.push("Sunday");
                    }
                } else {
                    me.selectedJobType("Temporary");
                    me.jobType("Temporary");
                    me.showTotalJobOpenings(true);
                }
                me.totalJobOpening(d.jobDetails.no_of_jobs);
                me.jobOfficeId(d.jobDetails.recruiter_office_id);
                for (i in d.recruiterOffices) {
                    me.selectedLocations.push(d.recruiterOffices[i].address);
                    me.allLocations.push(d.recruiterOffices[i]);
                }
                for(i in d.allOfficeTypes){
                    me.allOfficeTypes.push(d.allOfficeTypes[i].officetype_name);
                }
//                console.log('1',me.allOfficeTypes());
        });
    };
    me.showOfficeDetails = function (d, e) {
        me.selectedOffice([]);
        selectedId = $(e.currentTarget).val();
        for (i in d.allLocations()) {
            if (d.allLocations()[i].id == selectedId) {
                me.selectedOffice.push(new OfficeModel(d.allLocations()[i]));
            }
        }
//        console.log('2',me.selectedOffice()[0].selectedOfficeType());
        $('.ddlCars').multiselect({
            numberDisplayed: 3,
        });
        $(".dropCheck input").after("<div></div>");
    };
    
//    me.datePicker1 = function(d, e){
//        $(e.currentTarget).on("dp.change", function () {
//        });
//    };
    
    me.initializeMap = function(){
        var placeSearch, autocomplete, autocomplete1, autocomplete2, officeName;
        var componentForm = {
            postal_code: 'short_name'
        };

        var autocomplete = {};
        var autocompletesWraps = ['autocomplete', 'autocomplete1', 'autocomplete2'];

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

                    $('#postal_code' + indexField).val('');
                    for (var i = 0; i < place.address_components.length; i++) {
                        var addressType = place.address_components[i].types[0];
                        if (componentForm[addressType]) {
                            var val = place.address_components[i][componentForm[addressType]];
                            document.getElementById(addressType + indexField).value = val;
                        }
                    }

//                    document.getElementById('full_address' + indexField).value = place.formatted_address;
//                    document.getElementById('lat' + indexField).value = place.geometry.location.lat();
//                    document.getElementById('lng' + indexField).value = place.geometry.location.lng();
                    $('#' + name)[0].value = place.formatted_address;

                    checkLocation($('#postal_code' + indexField).val(), indexField);
                });
            });
        });
    };
    
    me.getOfficeName = function(d, e) {
        officeName = new google.maps.places.SearchBox(
                (document.getElementById('officeName')),
                {types: ['geocode']});
    };
    
    me.selecteJobType = function(d, e){
        checkJobType = $(e.currentTarget).prev().val();
        if( checkJobType == "Full Time"){
            me.selectedJobType("Full Time");
        }else if(checkJobType == "Part Time"){
            me.selectedJobType("Part Time");
        }else{
            me.selectedJobType('Temporary');
            $('#CoverStartDateOtherPicker').datepicker('setDates', [new Date(2017, 2, 20), new Date(2017, 2, 21)]);
        }
    };
    
    me.everyDayWorkHour = function(d, e){
        d.selectedOfficeWorkingHours.isMondayWork(false);
        d.selectedOfficeWorkingHours.isTuesdayWork(false);
        d.selectedOfficeWorkingHours.isWednesdayWork(false);
        d.selectedOfficeWorkingHours.isThursdayWork(false);
        d.selectedOfficeWorkingHours.isFridayWork(false);
        d.selectedOfficeWorkingHours.isSaturdayWork(false);
        d.selectedOfficeWorkingHours.isSundayWork(false);
    };
    
    me.otherDayWorkHour = function (d, e){
        d.selectedOfficeWorkingHours.isEverydayWork(false);
    };
    
    me.changeWorkingHour = function(d, e){
        console.log(d);
    }
    
    me.publishJob = function(d, e){
        console.log(d);
    }

    me._init = function () {
        me.getJobDetails();
        me.initializeMap();
    };
    me._init();
};
var ejObj = new EditJobVM();
ko.applyBindings(ejObj, $('#edit-job')[0])
//});
</script>
@endsection
