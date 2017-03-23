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
    me.errors = ko.observable(false);
    me.locationError = ko.observable('');
    me.officeTypeError = ko.observable('');
    me.errorMessage = ko.observable('');
    me.officeInfoError = ko.observable('');

    me._init = function (d) {
        if (typeof d == "undefined") {
            me.officeId = ko.observable(Math.floor((Math.random() * 100) + 1));
            me.officeAddress = ko.observable('');
            me.officePhone = ko.observable();
            me.officeInfo = ko.observable('');
            me.officeWorkingHours = ko.observable();
            me.officeZipcode = ko.observable();
            me.officeType = ko.observableArray([]);
            me.officeLat = ko.observable();
            me.officeLng = ko.observable();
            me.showOffice = ko.observable(false);
            me.showOfficeEditForm = ko.observable(true);
            me.alreadyAdded = ko.observable(false);
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
            me.errors = ko.observable(false);
            me.locationError = ko.observable('');
            me.officeWorkingHours = new WorkingHourModel(d);
        } else {
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
        }
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

    me.everyDayWorkHour = function (d, e) {
        if (d.officeWorkingHours.everydayStart() == null || d.officeWorkingHours.everydayStart() == "") {
            d.officeWorkingHours.everydayStart('08:00 AM');
            d.officeWorkingHours.everydayEnd('05:00 PM');
        }
        me.isMondayWork(false);
        me.isTuesdayWork(false);
        me.isWednesdayWork(false);
        me.isThursdayWork(false);
        me.isFridayWork(false);
        me.isSaturdayWork(false);
        me.isSundayWork(false);
    };

    me.otherDayWorkHour = function (d, e) {
        if ($(e.target).attr('id').indexOf('mon') >= 0) {
            if (d.officeWorkingHours.mondayStart() == null || d.officeWorkingHours.mondayStart() == "undefined") {
                d.officeWorkingHours.mondayStart('08:00 AM');
                d.officeWorkingHours.mondayEnd('05:00 PM');
            }
        }
        if ($(e.target).attr('id').indexOf('tue') >= 0) {
            if (d.officeWorkingHours.tuesdayStart() == null || d.officeWorkingHours.tuesdayStart() == "undefined") {
                d.officeWorkingHours.tuesdayStart('08:00 AM');
                d.officeWorkingHours.tuesdayEnd('05:00 PM');
            }
        }
        if ($(e.target).attr('id').indexOf('wed') >= 0) {
            if (d.officeWorkingHours.wednesdayStart() == null || d.officeWorkingHours.wednesdayStart() == "undefined") {
                d.officeWorkingHours.wednesdayStart('08:00 AM');
                d.officeWorkingHours.wednesdayEnd('05:00 PM');
            }
        }
        if ($(e.target).attr('id').indexOf('thu') >= 0) {
            if (d.officeWorkingHours.thursdayStart() == null || d.officeWorkingHours.thursdayStart() == "undefined") {
                d.officeWorkingHours.thursdayStart('08:00 AM');
                d.officeWorkingHours.thursdayEnd('05:00 PM');
            }
        }
        if ($(e.target).attr('id').indexOf('fri') >= 0) {
            if (d.officeWorkingHours.fridayStart() == null || d.officeWorkingHours.fridayStart() == "undefined") {
                d.officeWorkingHours.fridayStart('08:00 AM');
                d.officeWorkingHours.fridayEnd('05:00 PM');
            }
        }
        if ($(e.target).attr('id').indexOf('sat') >= 0) {
            if (d.officeWorkingHours.saturdayStart() == null || d.officeWorkingHours.saturdayStart() == "undefined") {
                d.officeWorkingHours.saturdayStart('08:00 AM');
                d.officeWorkingHours.saturdayEnd('05:00 PM');
            }
        }
        if ($(e.target).attr('id').indexOf('sun') >= 0) {
            if (d.officeWorkingHours.sundayStart() == null || d.officeWorkingHours.sundayStart() == "undefined") {
                d.officeWorkingHours.sundayStart('08:00 AM');
                d.officeWorkingHours.sundayEnd('05:00 PM');
            }
        }
        me.isEverydayWork(false);
    };

    me._init = function (d) {
        if (typeof d == "undefined") {
            me.isMondayWork(false);
            me.isTuesdayWork(false);
            me.isWednesdayWork(false);
            me.isThursdayWork(false);
            me.isFridayWork(false);
            me.isSaturdayWork(false);
            me.isSundayWork(false);
            me.isEverydayWork(false);
        } else {
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
    init: function (element, valueAccessor, bContext) {
        $(element).datetimepicker({
            format: 'hh:mm A',
            'allowInputToggle': true,
            stepping: 15,
            minDate: moment().startOf('day'),
            maxDate: moment().endOf('day')
        }).on('dp.change', function (a) {
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
    me.allOfficeTypeId = ko.observableArray([]);
    me.allOfficeTypeDetails = ko.observableArray([]);
    me.offices = ko.observableArray([]);
    me.showNameDescForm = ko.observable(false);
    me.showNameDesc = ko.observable(true);
    me.headMessage = ko.observable('');
    me.cancelButtonDelete = ko.observable(true);
    me.prompt = ko.observable('');
    me.showModalFooter = ko.observable(true);
    me.totalOffice = ko.observable();
    me.showAddMoreOfficeButton = ko.observable(false);
    me.addTotalOfText = ko.observable();
    me.disableAction = ko.observable(false);
    me.officeNameError = ko.observable('');
    me.officeDescError = ko.observable('');
    me.prevOfficeName = ko.observable('');
    me.prevOfficeDescription = ko.observable('');

    me.prevOfficeId = ko.observable();
    me.prevOfficeAddress = ko.observable('');
    me.prevOfficePhone = ko.observable();
    me.prevOfficeInfo = ko.observable('');
    me.prevOfficeZipcode = ko.observable();
    me.prevOfficeType = ko.observableArray([]);
    me.prevOfficeLat = ko.observable();
    me.prevOfficeLng = ko.observable();
    me.prevIsMondayWork = ko.observable(false);
    me.prevMondayStart = ko.observable(null);
    me.prevMondayEnd = ko.observable(null);
    me.prevIsTuesdayWork = ko.observable(false);
    me.prevTuesdayStart = ko.observable(null);
    me.prevTuesdayEnd = ko.observable(null);
    me.prevIsWednesdayWork = ko.observable(false);
    me.prevWednesdayStart = ko.observable(null);
    me.prevWednesdayEnd = ko.observable(null);
    me.prevIsThursdayWork = ko.observable(false);
    me.prevThursdayStart = ko.observable(null);
    me.prevThursdayEnd = ko.observable(null);
    me.prevIsFridayWork = ko.observable(false);
    me.prevFridayStart = ko.observable(null);
    me.prevFridayEnd = ko.observable(null);
    me.prevIsSaturdayWork = ko.observable(false);
    me.prevSaturdayStart = ko.observable(null);
    me.prevSaturdayEnd = ko.observable(null);
    me.prevIsSundayWork = ko.observable(false);
    me.prevSundayStart = ko.observable(null);
    me.prevSundayEnd = ko.observable(null);
    me.prevIsEverydayWork = ko.observable(false);
    me.prevEverydayStart = ko.observable(null);
    me.prevEverydayEnd = ko.observable(null);


    me.getProfileDetails = function () {
        jobId = $('#jobIdValue').val();
        $.get('recruiter-profile-details', {}, function (d) {
            if (typeof d.user != "undefined") {
                me.dentalOfficeName(d.user.office_name);
                me.dentalOfficeDescription(d.user.office_desc);
                me.recruiterProfileId(d.user.id);
            }
            for (i in d.officeType) {
                me.allOfficeTypes.push(d.officeType[i].officetype_name);
                me.allOfficeTypeId.push(d.officeType[i].id);
                me.allOfficeTypeDetails.push(d.officeType[i]);
            }
            for (i in d.offices) {
                me.offices.push(new OfficeModel(d.offices[i]));
            }

            me.totalOffice(d.offices.length);
            if (me.totalOffice() < 3) {
                me.showAddMoreOfficeButton(true);
            }
            me.addTotalOfText(3 - me.totalOffice());

        });
    };

    var placeSearch, autocomplete, autocomplete1, autocomplete2, officeName;
    var componentForm = {
        postal_code: 'short_name'
    };

    var autocomplete = {};
    var autocompletesWraps = ['autocomplete', 'autocomplete1', 'autocomplete2'];

    me.getOfficeName = function (d, e) {
        officeName = new google.maps.places.SearchBox(
                (e.currentTarget), {types: ['geocode']});
        officeName.addListener('places_changed', function () {
            var place = officeName.getPlaces();
            if (typeof place == "undefined") {
                return;
            }
            d.officeLat(place[0].geometry.location.lat());
            d.officeLng(place[0].geometry.location.lng());
            d.officeAddress(place[0].formatted_address);
            lastAddressComponent = place[0].address_components.pop().short_name;
            d.officeZipcode(lastAddressComponent);
            $.ajax({
                url: '/get-location/' + lastAddressComponent,
                type: "GET",
                before: function () {
                    me.locationError('');
                },
                success: function (data) {
                    if (data == 0) {
                        d.locationError('Please enter a valid address.');
                        d.errors(true);
                    } else if (data == 2) {
                        d.locationError('Job cannot be currently created for this location. We will soon be available in your area.');
                        d.errors(true);
                    } else {
                        d.errors(false);
                        d.locationError('');
                    }
                },
                error: function (data) {
                    me.locationError('Please enter a valid address.');
                    me.errors(true);
                }
            });
        });
    };

    me.showOfficeEditForm = function (d, e) {
        $(".phoneNumberInput").inputmask('(999)999 9999');
        me.prevOfficeId();
        me.prevOfficeAddress('');
        me.prevOfficePhone();
        me.prevOfficeInfo('');
        me.prevOfficeZipcode();
        me.prevOfficeType([]);
        me.prevOfficeLat();
        me.prevOfficeLng();
        me.prevIsMondayWork(false);
        me.prevMondayStart(null);
        me.prevMondayEnd(null);
        me.prevIsTuesdayWork(false);
        me.prevTuesdayStart(null);
        me.prevTuesdayEnd(null);
        me.prevIsWednesdayWork(false);
        me.prevWednesdayStart(null);
        me.prevWednesdayEnd(null);
        me.prevIsThursdayWork(false);
        me.prevThursdayStart(null);
        me.prevThursdayEnd(null);
        me.prevIsFridayWork(false);
        me.prevFridayStart(null);
        me.prevFridayEnd(null);
        me.prevIsSaturdayWork(false);
        me.prevSaturdayStart(null);
        me.prevSaturdayEnd(null);
        me.prevIsSundayWork(false);
        me.prevSundayStart(null);
        me.prevSundayEnd(null);
        me.prevIsEverydayWork(false);
        me.prevEverydayStart(null);
        me.prevEverydayEnd(null);

        $.get('job-applied-or-not', {officeId: d.officeId()}, function (data) {
            if (data.data.length == 0) {
                $('.ddlCars').multiselect({
                    numberDisplayed: 3,
                });
                $(".dropCheck input").after("<div></div>");

                me.prevOfficeId(d.officeId());
                me.prevOfficeAddress(d.officeAddress());
                me.prevOfficePhone(d.officePhone());
                me.prevOfficeInfo(d.officeInfo());
                me.prevOfficeZipcode(d.officeZipcode());
                for (i in d.officeType()) {
                    me.prevOfficeType.push(d.officeType()[i]);
                }
                me.prevOfficeLat(d.officeLat());
                me.prevOfficeLng(d.officeLng());
                me.prevIsMondayWork(d.officeWorkingHours.isMondayWork());
                me.prevMondayStart(d.officeWorkingHours.mondayStart());
                me.prevMondayEnd(d.officeWorkingHours.mondayEnd());
                me.prevIsTuesdayWork(d.officeWorkingHours.isTuesdayWork());
                me.prevTuesdayStart(d.officeWorkingHours.tuesdayStart());
                me.prevTuesdayEnd(d.officeWorkingHours.tuesdayEnd());
                me.prevIsWednesdayWork(d.officeWorkingHours.isWednesdayWork());
                me.prevWednesdayStart(d.officeWorkingHours.wednesdayStart());
                me.prevWednesdayEnd(d.officeWorkingHours.wednesdayEnd());
                me.prevIsThursdayWork(d.officeWorkingHours.isThursdayWork());
                me.prevThursdayStart(d.officeWorkingHours.thursdayStart());
                me.prevThursdayEnd(d.officeWorkingHours.thursdayEnd());
                me.prevIsFridayWork(d.officeWorkingHours.isFridayWork());
                me.prevFridayStart(d.officeWorkingHours.fridayStart());
                me.prevFridayEnd(d.officeWorkingHours.fridayEnd());
                me.prevIsSaturdayWork(d.officeWorkingHours.isSaturdayWork());
                me.prevSaturdayStart(d.officeWorkingHours.saturdayStart());
                me.prevSaturdayEnd(d.officeWorkingHours.saturdayEnd());
                me.prevIsSundayWork(d.officeWorkingHours.isSundayWork());
                me.prevSundayStart(d.officeWorkingHours.sundayStart());
                me.prevSundayEnd(d.officeWorkingHours.sundayEnd());
                me.prevIsEverydayWork(d.officeWorkingHours.isEverydayWork());
                me.prevEverydayStart(d.officeWorkingHours.everydayStart());
                me.prevEverydayEnd(d.officeWorkingHours.everydayEnd());

                d.showOfficeEditForm(true);
                d.showOffice(false);
            } else {
                me.headMessage('Edit Office');
                me.cancelButtonDelete(true);
                me.prompt('You cannot edit this office because this office has jobs.');
                me.showModalFooter(false);
                $('#actionModal').modal('show');
            }
        });
    };

    me.deleteOffice = function (d, e) {
        if (d.alreadyAdded() == true) {
            $.get('job-applied-or-not', {officeId: d.officeId()}, function (data) {
                if (data.data.length == 0) {
                    me.headMessage('Delete Office');
                    me.cancelButtonDelete(true);
                    me.prompt('Do you want to delete this office ?');
                    me.showModalFooter(true);
                    $('#actionModal').modal('show');
                    formData = new FormData();
                    formData.append('officeId', d.officeId());
                    $('#actionButton').click(function () {
                        me.prompt('Deleting office...');
                        me.cancelButtonDelete(false);
                        me.showModalFooter(false);
                        me.disableAction(false);
                        jQuery.ajax({
                            url: "delete-office",
                            data: formData,
                            cache: false,
                            contentType: false,
                            processData: false,
                            type: 'POST',
                            success: function (data) {
                                me.prompt('Office deleted successfully.');
                                if (data.success == true) {
                                    me.offices.remove(d);
                                    me.disableAction(false);
                                    setTimeout(
                                            function () {
                                                $('#actionModal').modal('hide');
                                            }, 700);
                                }
                            }
                        });
                    });
                } else {
                    me.headMessage('Delete Office');
                    me.cancelButtonDelete(true);
                    me.prompt('You cannot delete this office because this office has jobs.');
                    me.showModalFooter(false);
                    $('#actionModal').modal('show');
                }
            });
        } else {
            me.offices.remove(d);
        }
        me.showAddMoreOfficeButton(true);
    };

    me.updateOfficeDetails = function (d, e) {
        d.mixedWorkHourError('');
        d.mondayTimeError('');
        d.tuesdayTimeError('');
        d.wednesdayTimeError('');
        d.thursdayTimeError('');
        d.fridayTimeError('');
        d.saturdayTimeError('');
        d.sundayTimeError('');
        d.everydayTimeError('');
        d.phoneNumberError('');
        d.officeTypeError('');
        //        d.locationError('');
        d.officeInfoError('');

        if (d.officeType().length == 0) {
            d.officeTypeError('Please select atleast one type,');
            return false;
        }
        if (d.officeWorkingHours.isEverydayWork() == true && (d.officeWorkingHours.isMondayWork() == true || d.officeWorkingHours.isTuesdayWork() == true || d.officeWorkingHours.isWednesdayWork() == true || d.officeWorkingHours.isThursdayWork() == true || d.officeWorkingHours.isFridayWork() == true || d.officeWorkingHours.isSaturdayWork() == true || d.officeWorkingHours.isSundayWork() == true)) {
            d.mixedWorkHourError('Please select everyday or select individual day at a time.');
            return false;
        } else {
            if (d.officeWorkingHours.isEverydayWork() == true) {
                if (moment(d.officeWorkingHours.everydayStart(), 'HH:mm a') > moment(d.officeWorkingHours.everydayEnd(), 'HH:mm a')) {
                    d.everydayTimeError('Start time cannot be greated than end time.');
                    return false;
                }
                if (moment(d.officeWorkingHours.everydayStart(), 'HH:mm a').isSame(moment(d.officeWorkingHours.everydayEnd(), 'HH:mm a'))) {
                    d.everydayTimeError('Start time cannot be equal to end time.');
                    return false;
                }
            }
            if (d.officeWorkingHours.isMondayWork() == true) {
                if (moment(d.officeWorkingHours.mondayStart(), 'HH:mm a') > moment(d.officeWorkingHours.mondayEnd(), 'HH:mm a')) {
                    d.mondayTimeError('Start time cannot be greated than end time.');
                    return false;
                }
                if (moment(d.officeWorkingHours.mondayStart(), 'HH:mm a').isSame(moment(d.officeWorkingHours.mondayEnd(), 'HH:mm a'))) {
                    d.mondayTimeError('Start time cannot be equal to end time.');
                    return false;
                }
            }
            if (d.officeWorkingHours.isTuesdayWork() == true) {
                if (moment(d.officeWorkingHours.tuesdayStart(), 'HH:mm a') > moment(d.officeWorkingHours.tuesdayEnd(), 'HH:mm a')) {
                    d.tuesdayTimeError('Start time cannot be greated than end time.');
                    return false;
                }
                if (moment(d.officeWorkingHours.tuesdayStart(), 'HH:mm a').isSame(moment(d.officeWorkingHours.tuesdayEnd(), 'HH:mm a'))) {
                    d.tuesdayTimeError('Start time cannot be equal to end time.');
                    return false;
                }
            }
            if (d.officeWorkingHours.isWednesdayWork() == true) {
                if (moment(d.officeWorkingHours.wednesdayStart(), 'HH:mm a') > moment(d.officeWorkingHours.wednesdayEnd(), 'HH:mm a')) {
                    d.wednesdayTimeError('Start time cannot be greated than end time.');
                    return false;
                }
                if (moment(d.officeWorkingHours.wednesdayStart(), 'HH:mm a').isSame(moment(d.officeWorkingHours.wednesdayEnd(), 'HH:mm a'))) {
                    d.wednesdayTimeError('Start time cannot be equal to end time.');
                    return false;
                }
            }
            if (d.officeWorkingHours.isThursdayWork() == true) {
                if (moment(d.officeWorkingHours.thursdayStart(), 'HH:mm a') > moment(d.officeWorkingHours.thursdayEnd(), 'HH:mm a')) {
                    d.thursdayTimeError('Start time cannot be greated than end time.');
                    return false;
                }
                if (moment(d.officeWorkingHours.thursdayStart(), 'HH:mm a').isSame(moment(d.officeWorkingHours.thursdayEnd(), 'HH:mm a'))) {
                    d.thursdayTimeError('Start time cannot be equal to end time.');
                    return false;
                }
            }
            if (d.officeWorkingHours.isFridayWork() == true) {
                if (moment(d.officeWorkingHours.fridayStart(), 'HH:mm a') > moment(d.officeWorkingHours.fridayEnd(), 'HH:mm a')) {
                    d.fridayTimeError('Start time cannot be greated than end time.');
                    return false;
                }
                if (moment(d.officeWorkingHours.fridayStart(), 'HH:mm a').isSame(moment(d.officeWorkingHours.fridayEnd(), 'HH:mm a'))) {
                    d.fridayTimeError('Start time cannot be equal to end time.');
                    return false;
                }
            }
            if (d.officeWorkingHours.isSaturdayWork() == true) {
                if (moment(d.officeWorkingHours.saturdayStart(), 'HH:mm a') > moment(d.officeWorkingHours.saturdayEnd(), 'HH:mm a')) {
                    d.saturdayTimeError('Start time cannot be greated than end time.');
                    return false;
                }
                if (moment(d.officeWorkingHours.saturdayStart(), 'HH:mm a').isSame(moment(d.officeWorkingHours.saturdayEnd(), 'HH:mm a'))) {
                    d.saturdayTimeError('Start time cannot be equal to end time.');
                    return false;
                }
            }
            if (d.officeWorkingHours.isSundayWork() == true) {
                if (moment(d.officeWorkingHours.sundayStart(), 'HH:mm a') > moment(d.officeWorkingHours.sundayEnd(), 'HH:mm a')) {
                    d.sundayTimeError('Start time cannot be greated than end time.');
                    return false;
                }
                if (moment(d.officeWorkingHours.sundayStart(), 'HH:mm a').isSame(moment(d.officeWorkingHours.sundayEnd(), 'HH:mm a'))) {
                    d.sundayTimeError('Start time cannot be equal to end time.');
                    return false;
                }
            }
        }

        if (d.officeAddress() == null || d.officeAddress() == "") {
            d.locationError('Please enter address');
            return false;
        }

        if (d.officePhone() == null || d.officePhone() == '') {
            d.phoneNumberError('Please enter phone number.');
            return false;
        }
        
        if (d.officePhone().length > 14) {
            d.phoneNumberError('Phone number should be of 10 digits.');
            return false;
        }

        if (d.officeInfo() != "") {
            if (d.officeInfo().length > 500) {
                d.officeInfoError('Office info cannot be greater than 500 characters.');
            }
        }

        if (d.errors() == true) {
            return false;
        } else {
            me.headMessage('Updating Office');
            me.cancelButtonDelete(false);
            me.prompt('Updating office please wait.');
            me.showModalFooter(false);
            $('#actionModal').modal('show');
            formData = new FormData();
            formData.append('officeDetails', ko.toJSON(d));
            formData.append('officeId', d.officeId());
            if (d.alreadyAdded() == false) {
                formData.append('new', true);
            } else {
                formData.append('new', false);
            }
            jQuery.ajax({
                url: "edit-recruiter-office",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function (data) {
                    me.prompt('Office updated successfully.');
                    if (data.success == true) {
                        d.showOffice(true);
                        d.showOfficeEditForm(false);
                        if (d.alreadyAdded() == false) {
                            d.alreadyAdded(true);
                            d.officeId(data.recruiterOffice.id);
                        }
                        setTimeout(
                                function () {
                                    $('#actionModal').modal('hide');
                                }, 1000);
                    } else {
                        d.errorMessage(data.message);
                        me.prompt('Error in updating office.');
                        setTimeout(
                                function () {
                                    $('#actionModal').modal('hide');
                                }, 1000);
                    }
                }
            });
        }
    };

    me.cancelUpdateOffice = function (d, e) {
        if (d.alreadyAdded() == true) {
            d.officeId(me.prevOfficeId());
            d.officeAddress(me.prevOfficeAddress());
            d.officePhone(me.prevOfficePhone());
            d.officeInfo(me.prevOfficeInfo());
            d.officeZipcode(me.prevOfficeZipcode());
            d.officeType([]);
            for (i in me.prevOfficeType()) {
                d.officeType.push(me.prevOfficeType()[i]);
            }
            d.officeLat(me.prevOfficeLat());
            d.officeLng(me.prevOfficeLng());
            d.officeWorkingHours.isMondayWork(me.prevIsMondayWork());
            d.officeWorkingHours.mondayStart(me.prevMondayStart());
            d.officeWorkingHours.mondayEnd(me.prevMondayEnd());
            d.officeWorkingHours.isTuesdayWork(me.prevIsTuesdayWork());
            d.officeWorkingHours.tuesdayStart(me.prevTuesdayStart());
            d.officeWorkingHours.tuesdayEnd(me.prevTuesdayEnd());
            d.officeWorkingHours.isWednesdayWork(me.prevIsWednesdayWork());
            d.officeWorkingHours.wednesdayStart(me.prevWednesdayStart());
            d.officeWorkingHours.wednesdayEnd(me.prevWednesdayEnd());
            d.officeWorkingHours.isThursdayWork(me.prevIsThursdayWork());
            d.officeWorkingHours.thursdayStart(me.prevThursdayStart());
            d.officeWorkingHours.thursdayEnd(me.prevThursdayEnd());
            d.officeWorkingHours.isFridayWork(me.prevIsFridayWork());
            d.officeWorkingHours.fridayStart(me.prevFridayStart());
            d.officeWorkingHours.fridayEnd(me.prevFridayEnd());
            d.officeWorkingHours.isSaturdayWork(me.prevIsSaturdayWork());
            d.officeWorkingHours.saturdayStart(me.prevSaturdayStart());
            d.officeWorkingHours.saturdayEnd(me.prevSaturdayEnd());
            d.officeWorkingHours.isSundayWork(me.prevIsSundayWork());
            d.officeWorkingHours.sundayStart(me.prevSundayStart());
            d.officeWorkingHours.sundayEnd(me.prevSundayEnd());
            d.officeWorkingHours.isEverydayWork(me.prevIsEverydayWork());
            d.officeWorkingHours.everydayStart(me.prevEverydayStart());
            d.officeWorkingHours.everydayEnd(me.prevEverydayEnd());

            d.showOfficeEditForm(false);
            d.showOffice(true);
        } else {
            me.offices.remove(d);
        }
    };

    me.showUpdateNameDescForm = function (d, e) {
        d.prevOfficeName(d.dentalOfficeName());
        d.prevOfficeDescription(d.dentalOfficeDescription());
        d.showNameDescForm(true);
        d.showNameDesc(false);
    };

    me.cancelNameDescForm = function (d, e) {
        me.officeDescError('');
        me.officeNameError('');
        d.dentalOfficeName(d.prevOfficeName());
        d.dentalOfficeDescription(d.prevOfficeDescription());
        d.showNameDescForm(false);
        d.showNameDesc(true);
    };

    me.updateNameDesc = function (d, e) {
        me.officeDescError('');
        me.officeNameError('');
        if (d.dentalOfficeName() == "" || d.dentalOfficeName() == null) {
            me.officeNameError('Name cannot be empty.');
            return false;
        }
        if (d.dentalOfficeDescription() == "" || d.dentalOfficeDescription() == null) {
            me.officeDescError('Decscription cannot be empty.');
            return false;
        }

        me.headMessage('Updating Office Info');
        me.cancelButtonDelete(false);
        me.prompt('Updating office info please wait.');
        me.showModalFooter(false);
        $('#actionModal').modal('show');

        formData = new FormData();
        formData.append('profileId', d.recruiterProfileId());
        formData.append('officeName', d.dentalOfficeName());
        formData.append('officeDescription', d.dentalOfficeDescription());
        jQuery.ajax({
            url: "update-recruiter-info",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (data) {
                me.prompt('Office Info updated successfully.');
                if (data.success == true) {
                    d.showNameDescForm(false);
                    d.showNameDesc(true);
                    setTimeout(
                            function () {
                                $('#actionModal').modal('hide');
                            }, 1000);
                } else {
                    me.prompt('Error in updating office info.');
                    setTimeout(
                            function () {
                                $('#actionModal').modal('hide');
                            }, 1000);
                }
            }
        });
    };

    me.addOfficeFunction = function (d, e) {
        me.offices.push(new OfficeModel());
        $('.ddlCars').multiselect({
            numberDisplayed: 3,
        });
        $(".dropCheck input").after("<div></div>");
        $(".phoneNumberInput").inputmask('(999)999 9999');
    };

    me._init = function () {
        $('body').find('#ChildVerticalTab_1').find('li').removeClass('resp-tab-active');
        $('body').find('#ChildVerticalTab_1').find('li:nth-child(1)').addClass('resp-tab-active');
        me.getProfileDetails();
    };
    me._init();
};
var ejObj = new EditProfileVM();
ko.applyBindings(ejObj, $('#edit-profile')[0]);
//});
