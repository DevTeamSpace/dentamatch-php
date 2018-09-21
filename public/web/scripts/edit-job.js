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
            me.selectedOfficeInfo(d.office_info);
            me.selectedOfficeZipcode(d.zipcode);
            me.selectedOfficeWorkingHours = new WorkingHourModel(d);
            me.datePickerBinding(true);
            me.selectedOfficeLat(d.latitude);
            me.selectedOfficeLng(d.longitude);
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
                stepping: 15,
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
        me.preferredJobLocations = ko.observableArray([]);
        me.jobId = ko.observable();
        me.defaultSelectLocation = ko.observableArray([]);
        me.defaultSelectPreferredJobLocation = ko.observableArray([]);
        me.abcd = ko.observableArray([]);
        me.jobType = ko.observable('');
        me.totalJobOpening = ko.observable();
        me.payRate = ko.observable();
        me.jobOfficeId = ko.observable();
        me.showTotalJobOpenings = ko.observable(false);
        me.showPayRate = ko.observable(false);
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
        me.payRateError = ko.observable('');
        me.partTimeJobDaysError = ko.observable('');
        me.temporaryJobError = ko.observable('');
        me.officeTypeError = ko.observable('');
        me.allOfficeTypeDetail = ko.observableArray([]);
        me.headMessage = ko.observable('');
        me.cancelButtonDelete = ko.observable(true);
        me.prompt = ko.observable('');
        me.showModalFooter = ko.observable(true);

        me.getJobDetails = function () {
            jobId = $('#jobIdValue').val();
            $.get('/job/edit-details', {jobId: jobId}, function (d) {
                me.jobId(d.jobDetails.id);
                if (d.jobSeekerStatus != 0) {
                    me.cannotEdit(true);
                    me.showEdit(false);
                } else {
                    me.cannotEdit(false);
                    me.showEdit(true);
                }
                
                me.abcd.push(d.jobDetails.address);
                for (i in d.recruiterOffices) {
                    me.allLocations.push(d.recruiterOffices[i]);
                }
                me.defaultSelectLocation.push(d.jobDetails.address);
                
                for (i in d.preferredJobLocations) {
                    me.preferredJobLocations.push(d.preferredJobLocations[i]);
                }
                me.defaultSelectPreferredJobLocation.push(d.jobDetails.preferred_job_location_id);
                
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
                    me.showPayRate(true);
                    var splitedTempJobDates;
                    if(d.jobDetails.temp_job_dates != null){
                        splitedTempJobDates = d.jobDetails.temp_job_dates.split(',');
                    }
                    for(i in splitedTempJobDates){
                        if(me.tempJobDates().indexOf(splitedTempJobDates[i]) < 0){
                            me.tempJobDates.push(splitedTempJobDates[i]);
                        }
                    }
                    $('.job-opening').removeClass('hide');
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
                me.payRate(d.jobDetails.pay_rate);
                me.jobOfficeId(d.jobDetails.recruiter_office_id);
                
                for(i in d.allOfficeTypes){
                    me.allOfficeTypes.push(d.allOfficeTypes[i].officetype_name);
                    me.allOfficeTypeDetail.push(d.allOfficeTypes[i]);
                }
                office_Id = null;
                for(i in me.allLocations()){
                    if(me.abcd()[0] == me.allLocations()[i].address){
                        office_Id = me.allLocations()[i].id;
                    }
                }
                me.showOfficeDetailsTwo(me, office_Id);
        });
    };
    
    me.showOfficeDetails = function (d, e) {
        me.selectedOffice([]);
        selectedId = $(e.target).val();
        for (i in d.allLocations()) {
            if (d.allLocations()[i].address == selectedId) {
                me.selectedOffice.push(new OfficeModel(d.allLocations()[i]));
            }
        }
        $('.ddlCars').multiselect({
            numberDisplayed: 3,
        });
        $(".dropCheck input").after("<div></div>");
        $('#editPhoneNumber').inputmask("(999)999 9999");  //static mask
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
        $('#editPhoneNumber').inputmask("(999)999 9999");  //static mask
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
                        me.locationError('Position cannot currently be created for this location. We will soon be available in your area.');
                        //me.errors(true);
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
        me.payRateError('');
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
            me.showPayRate(true);
        }
    };
    
    me.everyDayWorkHour = function(d, e){
        if (d.selectedOfficeWorkingHours.everydayStart() == null || d.selectedOfficeWorkingHours.everydayStart() == "") {
            d.selectedOfficeWorkingHours.everydayStart('08:00 AM');
            d.selectedOfficeWorkingHours.everydayEnd('05:00 PM');
        }
        d.selectedOfficeWorkingHours.isMondayWork(false);
        d.selectedOfficeWorkingHours.isTuesdayWork(false);
        d.selectedOfficeWorkingHours.isWednesdayWork(false);
        d.selectedOfficeWorkingHours.isThursdayWork(false);
        d.selectedOfficeWorkingHours.isFridayWork(false);
        d.selectedOfficeWorkingHours.isSaturdayWork(false);
        d.selectedOfficeWorkingHours.isSundayWork(false);
    };
    
    me.otherDayWorkHour = function (d, e){
        if ($(e.target).attr('id').indexOf('mon') >= 0) {
            if (d.selectedOfficeWorkingHours.mondayStart() == null || d.selectedOfficeWorkingHours.mondayStart() == "undefined") {
                d.selectedOfficeWorkingHours.mondayStart('08:00 AM');
                d.selectedOfficeWorkingHours.mondayEnd('05:00 PM');
            }
        }
        if ($(e.target).attr('id').indexOf('tue') >= 0) {
            if (d.selectedOfficeWorkingHours.tuesdayStart() == null || d.selectedOfficeWorkingHours.tuesdayStart() == "undefined") {
                d.selectedOfficeWorkingHours.tuesdayStart('08:00 AM');
                d.selectedOfficeWorkingHours.tuesdayEnd('05:00 PM');
            }
        }
        if ($(e.target).attr('id').indexOf('wed') >= 0) {
            if (d.selectedOfficeWorkingHours.wednesdayStart() == null || d.selectedOfficeWorkingHours.wednesdayStart() == "undefined") {
                d.selectedOfficeWorkingHours.wednesdayStart('08:00 AM');
                d.selectedOfficeWorkingHours.wednesdayEnd('05:00 PM');
            }
        }
        if ($(e.target).attr('id').indexOf('thu') >= 0) {
            if (d.selectedOfficeWorkingHours.thursdayStart() == null || d.selectedOfficeWorkingHours.thursdayStart() == "undefined") {
                d.selectedOfficeWorkingHours.thursdayStart('08:00 AM');
                d.selectedOfficeWorkingHours.thursdayEnd('05:00 PM');
            }
        }
        if ($(e.target).attr('id').indexOf('fri') >= 0) {
            if (d.selectedOfficeWorkingHours.fridayStart() == null || d.selectedOfficeWorkingHours.fridayStart() == "undefined") {
                d.selectedOfficeWorkingHours.fridayStart('08:00 AM');
                d.selectedOfficeWorkingHours.fridayEnd('05:00 PM');
            }
        }
        if ($(e.target).attr('id').indexOf('sat') >= 0) {
            if (d.selectedOfficeWorkingHours.saturdayStart() == null || d.selectedOfficeWorkingHours.saturdayStart() == "undefined") {
                d.selectedOfficeWorkingHours.saturdayStart('08:00 AM');
                d.selectedOfficeWorkingHours.saturdayEnd('05:00 PM');
            }
        }
        if ($(e.target).attr('id').indexOf('sun') >= 0) {
            if (d.selectedOfficeWorkingHours.sundayStart() == null || d.selectedOfficeWorkingHours.sundayStart() == "undefined") {
                d.selectedOfficeWorkingHours.sundayStart('08:00 AM');
                d.selectedOfficeWorkingHours.sundayEnd('05:00 PM');
            }
        }
        d.selectedOfficeWorkingHours.isEverydayWork(false);
    };
    
    me.publishJob = function(){
        var startTimeError = 'Start time cannot be greated than end time.';
        var startEndTimeError = 'Start time cannot be equal to end time.';
        var partTimeDaySelectionMsg = 'Please select days for part time job.';
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
        me.payRateError('');
        me.partTimeJobDaysError('');
        me.temporaryJobError('');
        me.officeTypeError('');
        
        if(me.selectedJobType() == "Full Time"){
            
        }else if(me.selectedJobType() == "Part Time"){
            if(me.partTimeDays().length == 0){
                me.partTimeJobDaysError(partTimeDaySelectionMsg);
                return false;
            }
        }else{
            if(me.totalJobOpening() == null || me.totalJobOpening() == ''){
                me.totalJobOpeningError('Total job openings required');
                return false;
            }
            if(me.payRate() == null || me.payRate() == ''){
                me.payRateError('Pay rate required');
                return false;
            }
            if($('#CoverStartDateOtherPicker').val() == '' || $('#CoverStartDateOtherPicker').val() == null){
                me.temporaryJobError(partTimeDaySelectionMsg);
                return false;
            }
        }
        
        if(me.selectedOffice()[0].selectedOfficeType().length == 0){
            me.officeTypeError('Please select office type.');
            return false;
        }
        
        if(me.selectedOffice()[0].selectedOfficeWorkingHours.isEverydayWork() == true && (me.selectedOffice()[0].selectedOfficeWorkingHours.isMondayWork() == true || me.selectedOffice()[0].selectedOfficeWorkingHours.isTuesdayWork() == true || me.selectedOffice()[0].selectedOfficeWorkingHours.isWednesdayWork() == true || me.selectedOffice()[0].selectedOfficeWorkingHours.isThursdayWork() == true || me.selectedOffice()[0].selectedOfficeWorkingHours.isFridayWork() == true || me.selectedOffice()[0].selectedOfficeWorkingHours.isSaturdayWork() == true || me.selectedOffice()[0].selectedOfficeWorkingHours.isSundayWork() == true)){
            me.mixedWorkHourError('Please select everyday or select individual day at a time.');
            return false;
        }else{
            if(me.selectedOffice()[0].selectedOfficeWorkingHours.isEverydayWork() == true){
                if(moment(me.selectedOffice()[0].selectedOfficeWorkingHours.everydayStart(), 'HH:mm a') > moment(me.selectedOffice()[0].selectedOfficeWorkingHours.everydayEnd(), 'HH:mm a')){
                    me.everydayTimeError(startTimeError);
                    return false;
                }
                if(moment(me.selectedOffice()[0].selectedOfficeWorkingHours.everydayStart(), 'HH:mm a').isSame(moment(me.selectedOffice()[0].selectedOfficeWorkingHours.everydayEnd(), 'HH:mm a'))){
                    me.everydayTimeError(startEndTimeError);
                    return false;
                }
            }
            if(me.selectedOffice()[0].selectedOfficeWorkingHours.isMondayWork() == true){
                if(moment(me.selectedOffice()[0].selectedOfficeWorkingHours.mondayStart(), 'HH:mm a') > moment(me.selectedOffice()[0].selectedOfficeWorkingHours.mondayEnd(), 'HH:mm a')){
                    me.mondayTimeError(startTimeError);
                    return false;
                }
                if(moment(me.selectedOffice()[0].selectedOfficeWorkingHours.mondayStart(), 'HH:mm a').isSame(moment(me.selectedOffice()[0].selectedOfficeWorkingHours.mondayEnd(), 'HH:mm a'))){
                    me.mondayTimeError(startEndTimeError);
                    return false;
                }
            }
            if(me.selectedOffice()[0].selectedOfficeWorkingHours.isTuesdayWork() == true){
                if(moment(me.selectedOffice()[0].selectedOfficeWorkingHours.tuesdayStart(), 'HH:mm a') > moment(me.selectedOffice()[0].selectedOfficeWorkingHours.tuesdayEnd(), 'HH:mm a')){
                    me.tuesdayTimeError(startTimeError);
                    return false;
                }
                if(moment(me.selectedOffice()[0].selectedOfficeWorkingHours.tuesdayStart(), 'HH:mm a').isSame(moment(me.selectedOffice()[0].selectedOfficeWorkingHours.tuesdayEnd(), 'HH:mm a'))){
                    me.tuesdayTimeError(startEndTimeError);
                    return false;
                }
            }
            if(me.selectedOffice()[0].selectedOfficeWorkingHours.isWednesdayWork() == true){
                if(moment(me.selectedOffice()[0].selectedOfficeWorkingHours.wednesdayStart(), 'HH:mm a') > moment(me.selectedOffice()[0].selectedOfficeWorkingHours.wednesdayEnd(), 'HH:mm a')){
                    me.wednesdayTimeError(startTimeError);
                    return false;
                }
                if(moment(me.selectedOffice()[0].selectedOfficeWorkingHours.wednesdayStart(), 'HH:mm a').isSame(moment(me.selectedOffice()[0].selectedOfficeWorkingHours.wednesdayEnd(), 'HH:mm a'))){
                    me.wednesdayTimeError(startEndTimeError);
                    return false;
                }
            }
            if(me.selectedOffice()[0].selectedOfficeWorkingHours.isThursdayWork() == true){
                if(moment(me.selectedOffice()[0].selectedOfficeWorkingHours.thursdayStart(), 'HH:mm a') > moment(me.selectedOffice()[0].selectedOfficeWorkingHours.thursdayEnd(), 'HH:mm a')){
                    me.thursdayTimeError(startTimeError);
                    return false;
                }
                if(moment(me.selectedOffice()[0].selectedOfficeWorkingHours.thursdayStart(), 'HH:mm a').isSame(moment(me.selectedOffice()[0].selectedOfficeWorkingHours.thursdayEnd(), 'HH:mm a'))){
                    me.thursdayTimeError(startEndTimeError);
                    return false;
                }
            }
            if(me.selectedOffice()[0].selectedOfficeWorkingHours.isFridayWork() == true){
                if(moment(me.selectedOffice()[0].selectedOfficeWorkingHours.fridayStart(), 'HH:mm a') > moment(me.selectedOffice()[0].selectedOfficeWorkingHours.fridayEnd(), 'HH:mm a')){
                    me.fridayTimeError(startTimeError);
                    return false;
                }
                if(moment(me.selectedOffice()[0].selectedOfficeWorkingHours.fridayStart(), 'HH:mm a').isSame(moment(me.selectedOffice()[0].selectedOfficeWorkingHours.fridayEnd(), 'HH:mm a'))){
                    me.fridayTimeError(startEndTimeError);
                    return false;
                }
            }
            if(me.selectedOffice()[0].selectedOfficeWorkingHours.isSaturdayWork() == true){
                if(moment(me.selectedOffice()[0].selectedOfficeWorkingHours.saturdayStart(), 'HH:mm a') > moment(me.selectedOffice()[0].selectedOfficeWorkingHours.saturdayEnd(), 'HH:mm a')){
                    me.saturdayTimeError(startTimeError);
                    return false;
                }
                if(moment(me.selectedOffice()[0].selectedOfficeWorkingHours.saturdayStart(), 'HH:mm a').isSame(moment(me.selectedOffice()[0].selectedOfficeWorkingHours.saturdayEnd(), 'HH:mm a'))){
                    me.saturdayTimeError(startEndTimeError);
                    return false;
                }
            }
            if(me.selectedOffice()[0].selectedOfficeWorkingHours.isSundayWork() == true){
                if(moment(me.selectedOffice()[0].selectedOfficeWorkingHours.sundayStart(), 'HH:mm a') > moment(me.selectedOffice()[0].selectedOfficeWorkingHours.sundayEnd(), 'HH:mm a')){
                    me.sundayTimeError(startTimeError);
                    return false;
                }
                if(moment(me.selectedOffice()[0].selectedOfficeWorkingHours.sundayStart(), 'HH:mm a').isSame(moment(me.selectedOffice()[0].selectedOfficeWorkingHours.sundayEnd(), 'HH:mm a'))){
                    me.sundayTimeError(startEndTimeError);
                    return false;
                }
            }
        }
        
        if(me.selectedOffice()[0].selectedOfficePhone() == null || me.selectedOffice()[0].selectedOfficePhone() == ''){
            me.phoneNumberError('Please enter phone number.');
            return false;
        }
        
        if(me.selectedOffice()[0].selectedOfficePhone().length > 14){
            me.phoneNumberError('Phone number should be of 10 digits.');
            return false;
        }
        
        if(me.errors() == true){
            return false;
        }else{
            me.headMessage('Publishing Job');
            me.cancelButtonDelete(false);
            me.prompt('Updating job please wait...');
            me.showModalFooter(false);
            $('#actionModal').modal('show');
            splitedTempDates = ($('#CoverStartDateOtherPicker').val()).split(',');
            me.tempJobDates([]);
            for(i in splitedTempDates){
                me.tempJobDates.push(splitedTempDates[i]);
            }
            
            formData = new FormData();
            formData.append('jobDetails', ko.toJSON(me));
            formData.append('jobId', me.jobId())
            jQuery.ajax({
                url: "/edit-job",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function (data) {
//                    me.prompt('Job published successfully, please wait redirecting you...');
                    setTimeout(
                        function ()
                        {
                            if(data.data == null){
                                location.reload();
                            }else{
                                location.replace("/job/edit/"+data.data.id);
                            }
                        }, 700);
                }
            });
        }
    };
    
    me.cancelJob = function(d, e){
        location.replace("/job/details/"+me.jobId());
    };
    
    me.deleteJob = function(d, e){
        me.headMessage('Delete Job');
        me.cancelButtonDelete(true);
        me.prompt('Do you want to delete this job ?');
        me.showModalFooter(true);
        $('#actionModal').modal('show');
        
        $('#actionButton').click(function(){
            me.cancelButtonDelete(false);
            me.showModalFooter(false);
            formData = new FormData();
            formData.append('jobId', me.jobId())
            jQuery.ajax({
                url: "/delete-job",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function (data) {
                    me.prompt('Job delted successfully.');
                    setTimeout(
                        function ()
                        {
                            location.replace("/job/lists");
                        }, 700);
                }
            });
        });
    };

    me._init = function () {
        me.getJobDetails();
    };
    me._init();
};
var ejObj = new EditJobVM();
ko.applyBindings(ejObj, $('#edit-job')[0]);
//});