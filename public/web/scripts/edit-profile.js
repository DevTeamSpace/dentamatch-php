//$(function () {

    var OfficeModel = function (data) {
        var me = this;
        me.officeId = ko.observable();
        me.officeAddress = ko.observable('');
        me.officePhone = ko.observable();
        me.officeInfo = ko.observable('');
        me.officeWorkingHours = ko.observable();
        me.officeZipcode = ko.observable();
        me.officeType = ko.observableArray([]);
        me.officeLat = ko.observable();
        me.officeLng = ko.observable();
        me.showOffice = ko.observable(true);
        me.showOfficeEditForm = ko.observable(false);
        me.alreadyAdded = ko.observable(true);

        me._init = function (d) {
            me.officeId(d.id);
            splitOfficeType = d.officetype_names.split(',');
            for (i in splitOfficeType) {
                me.officeType.push(splitOfficeType[i]);
            }
            me.officeAddress(d.address);
            me.officePhone(d.phone_no);
            me.officeInfo(d.office_info);
            me.officeZipcode(d.zipcode);
            me.officeWorkingHours = new WorkingHourModel(d);
            me.officeLat(d.latitude);
            me.officeLng(d.longitude);
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
        
        me.everyDayWorkHour = function(d, e){
            me.isMondayWork(false);
            me.isTuesdayWork(false);
            me.isWednesdayWork(false);
            me.isThursdayWork(false);
            me.isFridayWork(false);
            me.isSaturdayWork(false);
            me.isSundayWork(false);
        };
        
        me.otherDayWorkHour = function (d, e){
            me.isEverydayWork(false);
        };
        
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

    var EditProfileVM = function () {
        var me = this;
        me.dentalOfficeName = ko.observable('');
        me.dentalOfficeDescription = ko.observable('');
        me.recruiterProfileId = ko.observable();
        me.allOfficeTypes = ko.observableArray([]);
        me.offices = ko.observableArray([]);
        
        me.getProfileDetails = function () {
            jobId = $('#jobIdValue').val();
            $.get('recruiter-profile-details', {}, function (d) {
                console.log(d);
                if(typeof d.user != "undefined"){
                    me.dentalOfficeName(d.user.office_name);
                    me.dentalOfficeDescription(d.user.office_desc);
                    me.recruiterProfileId(d.user.id);
                }
                for(i in d.officeType){
                    me.allOfficeTypes.push(d.officeType[i].officetype_name);
                }
                for(i in d.offices){
                    me.offices.push(new OfficeModel(d.offices[i]));
                }
                console.log(me.offices());
                return false;
                
        });
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

    me._init = function () {
        me.getProfileDetails();
    };
    me._init();
};
var ejObj = new EditProfileVM();
ko.applyBindings(ejObj, $('#edit-profile')[0]);
//});