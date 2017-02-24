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

    <form data-parsley-validate data-bind="submit: publishJob">
        <div class="row sec-mob">
            <div class="col-sm-6 mr-b-10 col-xs-6">
                <div class="section-title">Edit Job</div>
            </div>
            <div class="col-sm-6 text-right mr-b-10 col-xs-6" data-bind="visible: showEdit">
                <button type="button" class="btn-link mr-r-5">Cancel</button>

                <button type="submit" class="btn btn-primary pd-l-25 pd-r-25">Publish</button>
            </div>
            <div class="col-sm-6 text-right mr-b-10 col-xs-6" data-bind="visible: cannotEdit">
                <p class="pull-right">Cannot edit this job</p>
            </div>
        </div>

        <div class="commonBox cboxbottom padding-dentaltemplate">

            <div class="form-group custom-select">
                <label >Dental Office Address</label>
                <select id="select-office-address" data-bind="options: allLocations, optionsText: 'address', optionsValue: 'id', selectedOptions: location, event:{ change: $root.showOfficeDetails}">
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
                        <p class="error-div" data-bind="text: partTimeJobDaysError"></p>
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
                <label >Total Job Opening</label>
                <input name="noOfJobs" type="text" id="jobopening" class="form-control" data-parsley-required-message="Total job opening is required" data-bind="visible: showTotalJobOpenings,value: totalJobOpening, attr:{'data-parsley-required': showTotalJobOpenings}" />
                <p class="error-div" data-bind="text: totalJobOpeningError"></p>
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
                    <input type="text" value="" data-bind="click: $root.getOfficeName, value: selectedOfficeAddress, event: {change: $root.getOfficeName}" id="officeAddress" name="officeName" class="form-control txtBtnDisable"  data-parsley-required data-parsley-required-message="Required">
                    <p class="error-div" data-bind="text: $root.locationError"></p>
                    <!--<input type="text" class="form-control"  placeholder="Office name, Street, City, Zip Code and Country" data-parsley-required data-parsley-required-message="office address required" data-bind="value: selectedOfficeAddress">-->
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" class="form-control" data-parsley-required data-parsley-required-message="phone number required" data-parsley-maxlength="10" data-parsley-maxlength-message="number should be 10" data-parsley-trigger="keyup" data-parsley-type="digits"  data-bind="value: selectedOfficePhone">
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
                    <label>Office Location Information <i class="optional">(Optional)</i></label>
                    <textarea class="form-control txtHeight"  data-parsley-required data-parsley-required-message="location information required"  data-parsley-maxlength="500" data-parsley-maxlength-message="Charcter should be 500" data-bind="value: selectedOfficeInfo"></textarea>
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
        me.selectedOfficeLat = ko.observable();
        me.selectedOfficeLng = ko.observable();

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
            me.selectedOfficeLat(d.latitude);
            me.selectedOfficeLng(d.longitude);
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
            if ((d.work_everyday_start == "00:00:00" && d.work_everyday_end == "00:00:00") || (d.work_everyday_start == null && d.work_everyday_end == null)) {
                if (d.monday_start != "00:00:00" && d.monday_start != null) {
                    me.isMondayWork(true);
                    dates = me.getDateFunc(d.monday_start, d.monday_end);
                    me.mondayStart(moment(dates[0]).format('LT'));
                    me.mondayEnd(moment(dates[1]).format('LT'));
                }
                if (d.tuesday_start != "00:00:00" && d.tuesday_start != null) {
                    me.isTuesdayWork(true);
                    dates = me.getDateFunc(d.tuesday_start, d.tuesday_end);
                    me.tuesdayStart(moment(dates[0]).format('LT'));
                    me.tuesdayEnd(moment(dates[1]).format('LT'));
                }
                if (d.wednesday_start != "00:00:00" && d.wednesday_start != null) {
                    me.isWednesdayWork(true);
                    dates = me.getDateFunc(d.wednesday_start, d.wednesday_end);
                    me.wednesdayStart(moment(dates[0]).format('LT'));
                    me.wednesdayEnd(moment(dates[1]).format('LT'));
                }
                if (d.thursday_start != "00:00:00" && d.thursday_start != null) {
                    me.isThursdayWork(true);
                    dates = me.getDateFunc(d.thursday_start, d.thursday_end);
                    me.thursdayStart(moment(dates[0]).format('LT'));
                    me.thursdayEnd(moment(dates[1]).format('LT'));
                }
                if (d.friday_start != "00:00:00" && d.friday_start != null) {
                    me.isFridayWork(true);
                    dates = me.getDateFunc(d.friday_start, d.friday_end);
                    me.fridayStart(moment(dates[0]).format('LT'));
                    me.fridayEnd(moment(dates[1]).format('LT'));
                }
                if (d.saturday_start != "00:00:00" && d.saturday_start != null) {
                    me.isSaturdayWork(true);
                    dates = me.getDateFunc(d.saturday_start, d.saturday_end);
                    me.saturdayStart(moment(dates[0]).format('LT'));
                    me.saturdayEnd(moment(dates[1]).format('LT'));
                }
                if (d.sunday_start != "00:00:00" && d.sunday_start != null) {
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
        init: function (element, valueAccessor,bContext) {
            $(element).datetimepicker({
                format: 'hh:mm A',
                'allowInputToggle': true,
                minDate: moment().startOf('day'),
                maxDate: moment().endOf('day')
            }).on('dp.change',function(a){
                bContext().value($(this).val());
            });
        }
    };

    var EditJobVM = function () {
        var me = this;
        me.showEdit = ko.observable(true);
        me.cannotEdit = ko.observable(false);
        me.allLocations = ko.observableArray([]);
        me.jobId = ko.observable();
        me.location = ko.observableArray([]);
        me.jobType = ko.observable('');
        me.totalJobOpening = ko.observable();
        me.jobOfficeId = ko.observable();
        me.showTotalJobOpenings = ko.observable(false);
        me.selectedOffice = ko.observableArray([]);
        me.allOfficeTypes = ko.observableArray([]);
        me.selectedJobType = ko.observable('');
        me.allPartTimeDays = ko.observableArray([]);
        me.tempJobDates = ko.observableArray([]);
        me.partTimeDays = ko.observableArray([]);
        me.locationError = ko.observable('');
        me.errors = ko.observable(false);
        me.mixedWorkHourError = ko.observable('');
        me.mondayTimeError = ko.observable('');
        me.tuesdayTimeError = ko.observable('');
        me.wednesdayTimeError = ko.observable('');
        me.thursdayTimeError = ko.observable('');
        me.fridayTimeError = ko.observable('');
        me.saturdayTimeError = ko.observable('');
        me.sundayTimeError = ko.observable('');
        me.everydayTimeError = ko.observable('');
        me.phoneNumberError = ko.observable('');
        me.totalJobOpeningError = ko.observable('');
        me.partTimeJobDaysError = ko.observable('');
        me.temporaryJobError = ko.observable('');
        me.allOfficeTypeDetail = ko.observableArray([]);

        me.getJobDetails = function () {
            jobId = <?php echo json_encode($jobId) ?>;
            $.get('/job/edit-details', {jobId: jobId}, function (d) {
                me.jobId(d.jobDetails.id);
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
                    splitedTempJobDates = d.jobDetails.temp_job_dates.split(',');
                    for(i in splitedTempJobDates){
                        if(me.tempJobDates().indexOf(splitedTempJobDates[i]) < 0){
                            me.tempJobDates.push(splitedTempJobDates[i]);
                        }
                    }
                    var selectedDates = [];
                    for(i in me.tempJobDates()){
                        splitedDate = me.tempJobDates()[i].split('-');
                        year = splitedDate[0];
                        month = splitedDate[1];
                        day = splitedDate[2];
                        customCreateDate = new Date(year, month-1, day);
                        selectedDates.push(customCreateDate);
//                        $('#CoverStartDateOtherPicker').datepicker({format: 'YYYY-MM-DD'});
                    }
                    $('#CoverStartDateOtherPicker').datepicker('setDates', selectedDates);
                }
                me.totalJobOpening(d.jobDetails.no_of_jobs);
                me.jobOfficeId(d.jobDetails.recruiter_office_id);
                for (i in d.recruiterOffices) {
//                    me.selectedLocations.push(d.recruiterOffices[i].address);
                    me.allLocations.push(d.recruiterOffices[i]);
                }
                for(i in d.allOfficeTypes){
                    me.allOfficeTypes.push(d.allOfficeTypes[i].officetype_name);
                    me.allOfficeTypeDetail.push(d.allOfficeTypes[i]);
                }
                office_Id = null;
                for(i in me.allLocations()){
                    if(me.location() == me.allLocations()[i].id){
                        office_Id = me.location()
                    }
                }
                me.showOfficeDetailsTwo(me, office_Id);
        });
    };
    
    me.showOfficeDetails = function (d, e) {
        me.selectedOffice([]);
        selectedId = $(e.target).val();
        for (i in d.allLocations()) {
            if (d.allLocations()[i].id == selectedId) {
                me.selectedOffice.push(new OfficeModel(d.allLocations()[i]));
            }
        }
        $('.ddlCars').multiselect({
            numberDisplayed: 3,
        });
        $(".dropCheck input").after("<div></div>");
    };
    me.showOfficeDetailsTwo = function (d, selectedId) {
        if(selectedId == null){
            return false;
        }
        me.selectedOffice([]);
        for (i in d.allLocations()) {
            if (d.allLocations()[i].id == selectedId) {
                me.selectedOffice.push(new OfficeModel(d.allLocations()[i]));
            }
        }
        $('.ddlCars').multiselect({
            numberDisplayed: 3,
        });
        $(".dropCheck input").after("<div></div>");
    };

    var placeSearch, autocomplete, autocomplete1, autocomplete2, officeName;
    var componentForm = {
        postal_code: 'short_name'
    };

    var autocomplete = {};
    var autocompletesWraps = ['autocomplete', 'autocomplete1', 'autocomplete2'];

    me.getOfficeName = function(d, e) {
        officeName = new google.maps.places.SearchBox(
                (document.getElementById('officeAddress')),
                {types: ['geocode']});
        officeName.addListener('places_changed', function(){
            var place = officeName.getPlaces();
            if(typeof place == "undefined"){
                return;
            }
            d.selectedOfficeLat(place[0].geometry.location.lat());
            d.selectedOfficeLng(place[0].geometry.location.lng());
            d.selectedOfficeAddress(place[0].formatted_address);
            lastAddressComponent = place[0].address_components.pop().short_name;
            d.selectedOfficeZipcode(lastAddressComponent);
            $.ajax(
            {
                url: '/get-location/' + lastAddressComponent,
                type: "GET",
                before: function(){
                    me.locationError('');
                },
                success: function (data) {
                    if (data == 0) {
                        me.locationError('Please enter a valid address.');
                        me.errors(true);
                    } else if (data == 2) {
                        me.locationError('Job cannot be currently created for this location. We will soon be available in your area.');
                        me.errors(true);
                    }else{
                        me.errors(false);
                        me.locationError('');
                    }
                },
                error: function (data) {
                    me.locationError('Please enter a valid address.');
                    me.errors(true);
                }
            });
        });
    }
    
    me.selecteJobType = function(d, e){
        me.totalJobOpeningError('');
        me.partTimeJobDaysError('');
        me.temporaryJobError('');
        checkJobType = $(e.currentTarget).prev().val();
        if( checkJobType == "Full Time"){
            me.selectedJobType("Full Time");
        }else if(checkJobType == "Part Time"){
            me.selectedJobType("Part Time");
        }else{
            me.selectedJobType('Temporary');
            me.showTotalJobOpenings(true);
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
    
    me.publishJob = function(){
        me.mixedWorkHourError('');
        me.mondayTimeError('');
        me.tuesdayTimeError('');
        me.wednesdayTimeError('');
        me.thursdayTimeError('');
        me.fridayTimeError('');
        me.saturdayTimeError('');
        me.sundayTimeError('');
        me.everydayTimeError('');
        me.phoneNumberError('');
        me.totalJobOpeningError('');
        me.partTimeJobDaysError('');
        me.temporaryJobError('');
        
        if(me.selectedJobType() == "Full Time"){
            
        }else if(me.selectedJobType() == "Part Time"){
            if(me.partTimeDays().length == 0){
                me.partTimeJobDaysError('Please select days for part time job.');
                return false;
            }
        }else{
            if(me.totalJobOpening() == null || me.totalJobOpening() == ''){
                me.totalJobOpeningError('Total job openings required');
                return false;
            }
            if($('#CoverStartDateOtherPicker').val() == '' || $('#CoverStartDateOtherPicker').val() == null){
                me.temporaryJobError('Please select dates for part time job.');
                return false;
            }
        }
        
        if(me.selectedOffice()[0].selectedOfficeWorkingHours.isEverydayWork() == true && (me.selectedOffice()[0].selectedOfficeWorkingHours.isMondayWork() == true || me.selectedOffice()[0].selectedOfficeWorkingHours.isTuesdayWork() == true || me.selectedOffice()[0].selectedOfficeWorkingHours.isWednesdayWork() == true || me.selectedOffice()[0].selectedOfficeWorkingHours.isThursdayWork() == true || me.selectedOffice()[0].selectedOfficeWorkingHours.isFridayWork() == true || me.selectedOffice()[0].selectedOfficeWorkingHours.isSaturdayWork() == true || me.selectedOffice()[0].selectedOfficeWorkingHours.isSundayWork() == true)){
            me.mixedWorkHourError('Please select everyday or select individual day at a time.');
            return false;
        }else{
            if(me.selectedOffice()[0].selectedOfficeWorkingHours.isEverydayWork() == true){
                if(moment(me.selectedOffice()[0].selectedOfficeWorkingHours.everydayStart(), 'HH:mm a') > moment(me.selectedOffice()[0].selectedOfficeWorkingHours.everydayEnd(), 'HH:mm a')){
                    me.everydayTimeError('Start time cannot be greated than end time.');
                    return false;
                }
            }
            if(me.selectedOffice()[0].selectedOfficeWorkingHours.isMondayWork() == true){
                if(moment(me.selectedOffice()[0].selectedOfficeWorkingHours.mondayStart(), 'HH:mm a') > moment(me.selectedOffice()[0].selectedOfficeWorkingHours.mondayEnd(), 'HH:mm a')){
                    me.mondayTimeError('Start time cannot be greated than end time.');
                    return false;
                }
            }
            if(me.selectedOffice()[0].selectedOfficeWorkingHours.isTuesdayWork() == true){
                if(moment(me.selectedOffice()[0].selectedOfficeWorkingHours.tuesdayStart(), 'HH:mm a') > moment(me.selectedOffice()[0].selectedOfficeWorkingHours.tuesdayEnd(), 'HH:mm a')){
                    me.tuesdayTimeError('Start time cannot be greated than end time.');
                    return false;
                }
            }
            if(me.selectedOffice()[0].selectedOfficeWorkingHours.isWednesdayWork() == true){
                if(moment(me.selectedOffice()[0].selectedOfficeWorkingHours.wednesdayStart(), 'HH:mm a') > moment(me.selectedOffice()[0].selectedOfficeWorkingHours.wednesdayEnd(), 'HH:mm a')){
                    me.wednesdayTimeError('Start time cannot be greated than end time.');
                    return false;
                }
            }
            if(me.selectedOffice()[0].selectedOfficeWorkingHours.isThursdayWork() == true){
                if(moment(me.selectedOffice()[0].selectedOfficeWorkingHours.thursdayStart(), 'HH:mm a') > moment(me.selectedOffice()[0].selectedOfficeWorkingHours.thursdayEnd(), 'HH:mm a')){
                    me.thursdayTimeError('Start time cannot be greated than end time.');
                    return false;
                }
            }
            if(me.selectedOffice()[0].selectedOfficeWorkingHours.isFridayWork() == true){
                if(moment(me.selectedOffice()[0].selectedOfficeWorkingHours.fridayStart(), 'HH:mm a') > moment(me.selectedOffice()[0].selectedOfficeWorkingHours.fridayEnd(), 'HH:mm a')){
                    me.fridayTimeError('Start time cannot be greated than end time.');
                    return false;
                }
            }
            if(me.selectedOffice()[0].selectedOfficeWorkingHours.isSaturdayWork() == true){
                if(moment(me.selectedOffice()[0].selectedOfficeWorkingHours.saturdayStart(), 'HH:mm a') > moment(me.selectedOffice()[0].selectedOfficeWorkingHours.saturdayEnd(), 'HH:mm a')){
                    me.saturdayTimeError('Start time cannot be greated than end time.');
                    return false;
                }
            }
            if(me.selectedOffice()[0].selectedOfficeWorkingHours.isSundayWork() == true){
                if(moment(me.selectedOffice()[0].selectedOfficeWorkingHours.sundayStart(), 'HH:mm a') > moment(me.selectedOffice()[0].selectedOfficeWorkingHours.sundayEnd(), 'HH:mm a')){
                    me.sundayTimeError('Start time cannot be greated than end time.');
                    return false;
                }
            }
        }
        
        if(me.selectedOffice()[0].selectedOfficePhone() == null || me.selectedOffice()[0].selectedOfficePhone() == ''){
            me.phoneNumberError('Please enter phone number.');
            return false;
        }
        console.log(me);
        return false;
        if(me.errors() == true){
            return false;
        }else{
            splitedTempDates = ($('#CoverStartDateOtherPicker').val()).split(',');
            me.tempJobDates([]);
            for(i in splitedTempDates){
                me.tempJobDates.push(splitedTempDates[i]);
            }
            
            formData = new FormData();
            formData.append('jobDetails', ko.toJSON(me));
            formData.append('jobId', me.jobId())
            jQuery.ajax({
                url: "{{url('edit-job')}}",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function (data) {
                    console.log(data);
                }
            });
        }
    }

    me._init = function () {
        me.getJobDetails();
    };
    me._init();
};
var ejObj = new EditJobVM();
ko.applyBindings(ejObj, $('#edit-job')[0])
//});
</script>
@endsection