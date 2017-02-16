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

                <button type="button" class="btn btn-primary pd-l-25 pd-r-25">Publish</button>
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
                            <label for="Full Time">
                                Full Time
                            </label>
                        </div>  
                    </div>
                    <div class="col-md-4  col-lg-4 ">
                        <div class="full-time-box">
                            <input class="magic-radio" type="radio" name="radio" id="parttime" value="Part Time" data-bind="checked: jobType">
                            <label for="Part Time">
                                Part Time
                            </label>
                            <select multiple="multiple" id="monthSelect" style="display: none;" class="select-days-custom">
                                <option value="1" data-bind="attr: {selected: isMonday}">Monday</option>
                                <option value="2" data-bind="attr: {selected: isTuesday}">Tuesday</option>
                                <option value="3" data-bind="attr: {selected: isWednesday}">Wednesday</option>
                                <option value="4" data-bind="attr: {selected: isThursday}">Thursday</option>
                                <option value="5" data-bind="attr: {selected: isFriday}">Friday</option>
                                <option value="6" data-bind="attr: {selected: isSaturday}">Saturday</option>
                                <option value="7" data-bind="attr: {selected: isSunday}">Sunday</option>
                            </select>
                        </div>  
                    </div>
                    <div class="col-md-4 col-lg-4 ">
                        <div class="full-time-box">
                            <input class="magic-radio" type="radio" name="radio" id="temporary" value="Temporary" data-bind="checked: jobType">

                            <label for="Temporary">
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
                    <div class="custom-select">
                        <select  id="dentalofficetype"  class="selectpicker">
                            <option value="Smiley">Orthodontist, Oral Surgeon</option>

                        </select>
                        <!--<p class="error-div">Job cannot be currently created for this location. We will soon be available in your area.</p>-->
                    </div>
                </div>
                <div class="form-group">
                    <label>Dental Office Address</label>
                    <input type="text" class="form-control"  placeholder="Office name, Street, City, Zip Code and Country" data-parsley-required data-parsley-required-message="office address required" data-bind="value: selectedOfficeAddress">
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
                                <input type="checkbox" id="test2" data-bind="checked: selectedOfficeWorkingHours.isEverydayWork" />
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
                                    <input type="checkbox" id="mon" data-bind="checked: selectedOfficeWorkingHours.isMondayWork" />
                                    <label for="mon" class="ckColor"> Monday</label>
                                </p>    
                            </div>
                            <div class="col-sm-4 col-md-3">
                                <div class="date datetime1">
                                    <input type="text" class="form-control" placeholder="Opening Hours" data-parsley-required data-parsley-required-message="opening hours required" data-bind="value: selectedOfficeWorkingHours.mondayStart, disable: !selectedOfficeWorkingHours.isMondayWork()">
                                </div>    
                            </div>
                            <div class="col-sm-4 col-md-3">
                                <div class="date datetime1">
                                    <input type="text" class="form-control" placeholder="Closing Hours" data-parsley-required data-parsley-required-message="closing hours required" data-bind="value: selectedOfficeWorkingHours.mondayEnd, disable: !selectedOfficeWorkingHours.isMondayWork()" >
                                </div>
                            </div>
                        </div>
                        <div class="row dayBox">
                            <div class="col-sm-4 col-md-3">  
                                <p class="ckBox">
                                    <input type="checkbox" id="tue" data-bind="checked: selectedOfficeWorkingHours.isTuesdayWork" />
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
                                    <input type="checkbox" id="wed" data-bind="checked: selectedOfficeWorkingHours.isWednesdayWork" />
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
                                    <input type="checkbox" id="thu" data-bind="checked: selectedOfficeWorkingHours.isThursdayWork" />
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
                                    <input type="checkbox" id="fri" data-bind="checked: selectedOfficeWorkingHours.isFridayWork" />
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
                                    <input type="checkbox" id="sat" data-bind="checked: selectedOfficeWorkingHours.isSaturdayWork" />
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
                                    <input type="checkbox" id="sun" data-bind="checked: selectedOfficeWorkingHours.isSundayWork" />
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
<script src="{{asset('web/scripts/multiple-select.js')}}"></script>
<script>
    
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$(function () {
var JobModel = function (data) {
    var me = this;
//    me.location = ko.observableArray([]);
//    me.jobType = ko.observable('');
//    me.totalJobOpening = ko.observable();
//    me.jobOfficeId = ko.observable();
//    me.showTotalJobOpenings = ko.observable(false);
//    me.isMonday = ko.observable(false);
//    me.isTuesday = ko.observable(false);
//    me.isWednesday = ko.observable(false);
//    me.isThursday = ko.observable(false);
//    me.isFriday = ko.observable(false);
//    me.isSaturday = ko.observable(false);
//    me.isSunday = ko.observable(false);

    me._init = function (d) {
        if (typeof d == "undefined") {
            return false;
        }
//        me.location.push(d.address);
//        if(d.job_type == 1){
//            me.jobType("Full Time");
//        }else if(d.job_type == 2){
//            me.jobType("Part Time");
//        }else{
//            me.jobType("Temporary");
//            me.showTotalJobOpenings(true);
//            if(d.is_monday != null){
//                me.isMonday(true);
//            }if(d.is_tuesday != null){
//                me.isTuesday(true);
//            }if(d.is_wednesday != null){
//                me.isWednesday(true);
//            }if(d.is_thursday != null){
//                me.isThursday(true);
//            }if(d.is_friday != null){
//                me.isFriday(true);
//            }if(d.is_saturday != null){
//                me.isSaturday(true);
//            }if(d.is_sunday != null){
//                me.isSunday(true);
//            }
//        }
//        me.totalJobOpening(d.no_of_jobs);
//        me.jobOfficeId(d.recruiter_office_id);
    }
    me._init(data);
    return me;
};

var OfficeModel = function (data) {
    var me = this;
    me.selectedOfficeId = ko.observable();
    me.selectedOfficeType = ko.observable();
    me.selectedOfficeAddress = ko.observable('');
    me.selectedOfficePhone = ko.observable();
    me.selectedOfficeInfo = ko.observable('');
    me.selectedOfficeWorkingHours = ko.observable();
    me.selectedOfficeZipcode = ko.observable();
    me.datePickerBinding = ko.observable(false);

    me._init = function (d) {
        me.selectedOfficeId(d.id);
        me.selectedOfficeType(d.office_type_name);
        me.selectedOfficeAddress(d.address);
        me.selectedOfficePhone(d.phone_no);
        me.selectedOfficeInfo(d.office_location);
        me.selectedOfficeZipcode(d.zipcode);
        me.selectedOfficeWorkingHours = new WorkingHourModel(d);
        me.datePickerBinding(true);
    };

    me._init(data);
};

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
        console.log(d);
        if (d.work_everyday_start == "00:00:00" && d.work_everyday_end == "00:00:00") {
            if (d.monday_start != "00:00:00") {
                me.isMondayWork(true);
                me.mondayStart(moment(Date(d.monday_start)).format('LT'));
                me.mondayEnd(moment(Date(d.monday_end)).format('LT'));
            }
            if (d.tuesday_start != "00:00:00") {
                me.isTuesdayWork(true);
                me.tuesdayStart(moment(Date(d.tuesday_start)).format('LT'));
                me.tuesdayEnd(moment(Date(d.tuesday_end)).format('LT'));
            }
            if (d.wednesday_start != "00:00:00") {
                me.isWednesdayWork(true);
                me.wednesdayStart(moment(Date(d.wednesday_start)).format('LT'));
                me.wednesdayEnd(moment(Date(d.wednesday_end)).format('LT'));
            }
            if (d.thursday_start != "00:00:00") {
                me.isThursdayWork(true);
                me.thursdayStart(moment(Date(d.thursday_start)).format('LT'));
                me.thursdayEnd(moment(Date(d.thursday_end)).format('LT'));
            }
            if (d.friday_start != "00:00:00") {
                me.isFridayWork(true);
                me.fridayStart(moment(Date(d.friday_start)).format('LT'));
                me.fridayEnd(moment(Date(d.friday_end)).format('LT'));
            }
            if (d.saturday_start != "00:00:00") {
                me.isSaturdayWork(true);
                me.saturdayStart(moment(Date(d.saturday_start)).format('LT'));
                me.saturdayEnd(moment(Date(d.saturday_end)).format('LT'));
            }
            if (d.sunday_start != "00:00:00") {
                me.isSundayWork(true);
                me.sundayStart(moment(Date(d.sunday_start)).format('LT'));
                me.sundayEnd(moment(Date(d.sunday_end)).format('LT'));
            }
        } else {
            me.isEverydayWork(true);
            me.everydayStart(moment(Date(d.work_everyday_start)).format('LT'));
            me.everydayEnd(moment(Date(d.work_everyday_end)).format('LT'));
        }
    };

    me._init(data);
};

ko.bindingHandlers.datetimePicker = {
    init: function(element, valueAccessor){
        $('.datetime1').datetimepicker({
            format: 'hh:mm A',
                        'allowInputToggle' : true,
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
    me.isMonday = ko.observable(false);
    me.isTuesday = ko.observable(false);
    me.isWednesday = ko.observable(false);
    me.isThursday = ko.observable(false);
    me.isFriday = ko.observable(false);
    me.isSaturday = ko.observable(false);
    me.isSunday = ko.observable(false);
    me.selectedOffice = ko.observableArray([]);

    me.getJobDetails = function () {
        jobId = <?php echo json_encode($jobId) ?>;
        $.get('/job/edit-details', {jobId: jobId}, function (d) {
            if (d.jobSeekerStatus != 0) {
                me.cannotEdit(true);
                me.showEdit(false);
            } else {
                me.cannotEdit(false);
                me.showEdit(true);
            }
            me.location.push(d.address);
            if (d.jobDetails.job_type == 1) {
                me.jobType("Full Time");
            } else if (d.jobDetails.job_type == 2) {
                me.jobType("Part Time");
                if (d.jobDetails.is_monday !== null && d.jobDetails.is_monday !== 0) {
                    me.isMonday(true);
                }
                if (d.jobDetails.is_tuesday !== null && d.jobDetails.is_tuesday !== 0) {
                    me.isTuesday(true);
                }
                if (d.jobDetails.is_wednesday !== null && d.jobDetails.is_wednesday !== 0) {
                    me.isWednesday(true);
                }
                if (d.jobDetails.is_thursday !== null && d.jobDetails.is_thursday !== 0) {
                    me.isThursday(true);
                }
                if (d.jobDetails.is_friday !== null && d.jobDetails.is_friday !== 0) {
                    me.isFriday(true);
                }
                if (d.jobDetails.is_saturday !== null && d.jobDetails.is_saturday !== 0) {
                    me.isSaturday(true);
                }
                if (d.jobDetails.is_sunday !== null && d.jobDetails.is_sunday !== 0) {
                    me.isSunday(true);
                }
            } else {
                me.jobType("Temporary");
                me.showTotalJobOpenings(true);
            }
            me.totalJobOpening(d.no_of_jobs);
            me.jobOfficeId(d.recruiter_office_id);
            for (i in d.recruiterOffices) {
                me.selectedLocations.push(d.recruiterOffices[i].address);
                me.allLocations.push(d.recruiterOffices[i]);
            }
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
        console.log(me.selectedOffice());
    };
    
    me.datePicker1 = function(d, e){
        
        $(e.currentTarget).on("dp.change", function () {
            
        });
    };
    
    me.datePicker2 = function(d, e){
        
        $(e.currentTarget).on("dp.change", function () {
            
        });
    };

    me._init = function () {
        me.getJobDetails();
    };
    me._init();
};
var ejObj = new EditJobVM();
ko.applyBindings(ejObj, $('#edit-job')[0])
});
</script>
@endsection
