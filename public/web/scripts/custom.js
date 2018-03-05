$(function() {
    "use strict";
   
    var dynamicCount = 0;
    var dynamicLength, selVal, selLen;
    var locationNum = 3;
    $(".dropCheck").find("input").after("<div></div>");
// $(".dropdown-menu .inner>li").each(function(index,value){
// $(this).attr("chetan",value);

// });


    function dropDownDynamic() {
        $('.ddlCars').multiselect({
            numberDisplayed: 3,
        });
    }

    function lengthRestriction(event) {
        dynamicLength = $(".mainMasterBox").length;
        console.log(dynamicLength);
        var total = 3 - dynamicLength;
        $(".DynamicAddder").html('<span class="icon icon-plus"></span>You can add upto ' + total + ' more locations');
        if (dynamicLength >= 3) {
            $(".addBtn").removeClass("DynamicAddder");
            $(".addBtn").empty();
        }
    }

    function DynamicTickName() {
        $(".weekBox:last .ckBox").each(function(index) {
            $(this).children("input:checkbox").attr("id", "dynamicTest" + dynamicCount);
            $(this).children("label").attr("for", "dynamicTest" + dynamicCount);
            dynamicCount++;
        });
    }

    function WeekOption() {
        $('.EveryDayCheck').find('input:checkbox').on('change', function() {
            if ($(this).prop("checked")) {
                $(this).parents(".EveryDayCheck").siblings(".allDay").hide();
            } else {
                $(this).parents(".EveryDayCheck").siblings(".allDay").show();
            }
        });
    }

    function dynamicBox() {
        dynamicLength = $(".mainMasterBox").length;

        var masterCLone, dynamicDiv, currentEvent;
        if ($(this).hasClass('DynamicAddder')) {
            currentEvent = $(this);
            dynamicDiv = $(document.body).find(".profieBox").append('<div class="mainMasterBox"><div class="commonBox cboxbottom masterBox"></div><div class="pull-right text-right pd-b-15" id="removeButton' + dynamicLength + '"></div><div class="clearfix"></div></div> ');
            
            var officeTypes = $.parseJSON($('#officeTypesJson').val());
            var options = "";
            $.each(officeTypes, function(index, value) {
                options += "<option value='" + value.id + "'>" + value.officetype_name + "</option>";
            });
            var hiddenFields = '<div id="officeDetail-errors' + dynamicLength + '"></div><input type="hidden" id="postal_code' + dynamicLength + '" name="postal_code' + dynamicLength + '" data-parsley-required  ><input type="hidden" name="lat' + dynamicLength + '" id="lat' + dynamicLength + '"><input type="hidden" name="lng' + dynamicLength + '" id="lng' + dynamicLength + '"><input type="hidden" name="full_address' + dynamicLength + '" id="full_address' + dynamicLength + '">';
            masterCLone = hiddenFields + '<p class="deleteCard pull-right"><span class="icon icon-deleteicon"></span>Delete<div class=form-group><div class=detailTitleBlock><h5>OFFICE DETAILS</h5></div><label>Dental Office Type</label><div class=slt><select name="officeType' + dynamicLength + '[]" class=ddlCars data-parsley-required data-parsley-required-message=" required"multiple>' + options + ' </select></div></div><div class=form-group><label>Dental Office Address</label><input id="autocomplete' + dynamicLength + '" name="officeAddress' + dynamicLength + '" class=form-control data-parsley-required data-parsley-required-message="required" placeholder="Office name, Street, City, Zip Code and Country"><div id="location-msg' + dynamicLength + '"></div></div><div class=form-group><label>Phone Number</label><input  id="phoneNumber' + dynamicLength + '" name="phoneNumber' + dynamicLength + '" type="text" class="form-control phone-number" data-parsley-required data-parsley-required-message="Please, Provide a valid Phone number of 10 digits"   data-parsley-trigger="keyup" data-parsley-minlength="14"   data-parsley-minlength-message="phone number should be 10 digit"></div><div class=allCheckBox><div class=form-group><label>Working Hours</label><div class=weekBox><div class="dayBox row EveryDayCheck"><div class=col-sm-4><p class=ckBox><input id=test2 type=checkbox name="everyday' + dynamicLength + '" value="1" ><label class=ckColor for=test2>Everyday</label></div><div class=col-sm-4><div class="customsel date input-group datetimepicker1"><input name="everydayStart' + dynamicLength + '" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div><div class=col-sm-4><div class="customsel date input-group datetimepicker2"><input name="everydayEnd' + dynamicLength + '" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div></div><div class=allDay><div class="dayBox row"><div class=col-sm-4><p class=ckBox><input id=mon name="monday' + dynamicLength + '" value="1" type=checkbox><label class=ckColor for=mon>Monday</label></div><div class=col-sm-4><div class="customsel date input-group datetimepicker1"><input name="mondayStart' + dynamicLength + '"class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div><div class=col-sm-4><div class="customsel date input-group datetimepicker2"><input name="mondayEnd' + dynamicLength + '" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div></div><div class="dayBox row"><div class=col-sm-4><p class=ckBox><input id=tue name="tuesday' + dynamicLength + '" value="1" type=checkbox><label class=ckColor for=tue>Tuesday</label></div><div class=col-sm-4><div class="customsel date input-group datetimepicker1"><input name="tuesdayStart' + dynamicLength + '" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div><div class=col-sm-4><div class="customsel date input-group datetimepicker2"><input name="tuesdayEnd' + dynamicLength + '" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div></div><div class="dayBox row"><div class=col-sm-4><p class=ckBox><input id=wed name="wednesday' + dynamicLength + '" value="1" type=checkbox><label class=ckColor for=wed>Wednesday</label></div><div class=col-sm-4><div class="customsel date input-group datetimepicker1"><input name="wednesdayStart' + dynamicLength + '" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div><div class=col-sm-4><div class="customsel date input-group datetimepicker2"><input name="wednesdayEnd' + dynamicLength + '" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div></div><div class="dayBox row"><div class=col-sm-4><p class=ckBox><input id=thu name="thrusday' + dynamicLength + '" value="1" type=checkbox><label class=ckColor for=thu>Thursday</label></div><div class=col-sm-4><div class="customsel date input-group datetimepicker1"><input name="thrusdayStart' + dynamicLength + '" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div><div class=col-sm-4><div class="customsel date input-group datetimepicker2"><input name="thrusdayEnd' + dynamicLength + '" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div></div><div class="dayBox row"><div class=col-sm-4><p class=ckBox><input id=fri name="friday' + dynamicLength + '" value="1" type=checkbox><label class=ckColor for=fri>Friday</label></div><div class=col-sm-4><div class="customsel date input-group datetimepicker1"><input name="fridayStart' + dynamicLength + '" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div><div class=col-sm-4><div class="customsel date input-group datetimepicker2"><input name="fridayEnd' + dynamicLength + '" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div></div><div class="dayBox row"><div class=col-sm-4><p class=ckBox><input id=sat name="saturday' + dynamicLength + '" value="1" type=checkbox><label class=ckColor for=sat>Saturday</label></div><div class=col-sm-4><div class="customsel date input-group datetimepicker1"><input name="saturdayStart' + dynamicLength + '" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div><div class=col-sm-4><div class="customsel date input-group datetimepicker2"><input name="saturdayEnd' + dynamicLength + '" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div></div><div class="dayBox row"><div class=col-sm-4><p class=ckBox><input name="sunday' + dynamicLength + '" value="1" id=sun type=checkbox><label class=ckColor for=sun>Sunday</label></div><div class=col-sm-4><div class="customsel date input-group datetimepicker1"><input name="sundayStart' + dynamicLength + '" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div><div class=col-sm-4><div class="customsel date input-group datetimepicker2"><input name="sundayEnd' + dynamicLength + '" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div></div></div></div></div></div><div class=form-group><label>Office Location Information (Optional)</label><textarea id="officeLocation' + dynamicLength + '" name="officeLocation' + dynamicLength + '" class="form-control txtHeight"data-parsley-maxlength=500 data-parsley-maxlength-message="Charcter should be 500"  ></textarea></div>';


            $(".masterBox:last").html(masterCLone);
            $("#officeLocation"+ dynamicLength).Editor();
            dropDownDynamic();
            $(".masterBox:last").find(".dropCheck input").after("<div></div>");
            DynamicTickName();
            WeekOption();
            initializeMap();
            //-----datePicker---//
            var $startTime1 = $('.datetimepicker1');
            var $endTime1 = $('.datetimepicker2');

            $startTime1.datetimepicker({
                format: 'hh:mm A',
                'allowInputToggle': true,

                stepping: 15,

                minDate: moment().startOf('day'),
                maxDate: moment().endOf('day')

            });

            $endTime1.datetimepicker({
                format: 'hh:mm A',
                'allowInputToggle': true,
                useCurrent: false,
                stepping: 15,
                minDate: moment().startOf('day'),
                maxDate: moment().endOf('day')
            });


            $('.datetimepicker1').on("dp.change", function(e) {

                var date = $(this).data('date');

                $(this).parents(".row").find('.datetimepicker2').data('DateTimePicker').minDate(e.date);
                console.log(date);
            });
            $('.datetimepicker2').on("dp.change", function(e) {
                var date = $(this).data('date');
                $(this).parents(".row").find('.datetimepicker1').data('DateTimePicker').maxDate(e.date);
                console.log(date);
            });


            //-----datePicker---//
        } else {
            console.log("not exists");
        }
        lengthRestriction();
    }
    WeekOption();

    function deleteBox() {
        $(this).parents(".mainMasterBox").remove();
        dynamicLength = $(".mainMasterBox").length;
        var total = 3 - dynamicLength; 
        $(".addBtn").addClass("DynamicAddder");
        $(".DynamicAddder").html('<span class="icon icon-plus"></span>You can add upto ' + total + ' more locations');

    }
    //----- customValidation-----//
    function checkBoxValidation(e) {
        var parentBx = $(this).parents(".dayBox");
        if ($(this).prop("checked") == true) {

            selVal = parentBx.find(".customsel");
            selLen = parentBx.find(".customsel").length;
            $(selVal).find("input").prop("disabled", false);
            $(e.currentTarget).parents('.dayBox').find(".datetimepicker1 input").val("08:00 AM");
            $(e.currentTarget).parents('.dayBox').find(".datetimepicker2 input").val("05:00 PM");


            $(".customsel").on("dp.change", function(e) {
                if ($(this).find("input").prop("disabled") == false) {
                    if ($(this).find("input").val() == "") {
                        $(this).find("input").siblings(".parsley-errors-list").html("<li>Required</li>");
                    } else {
                        $(this).find("input").siblings(".parsley-errors-list").find("li").remove();
                    }
                }
            });
        } else {

            var InputPickerval = $(this).parents(".dayBox").find(".customsel").find('input');
            InputPickerval.siblings(".parsley-errors-list").find("li").remove();
            $(InputPickerval).val("");
            $(InputPickerval).prop("disabled", true);
        }
    }

    function blackValueCheck() {
        for (var valChange = 0; valChange < selLen; valChange++) {
            if ($(selVal).eq(valChange).find("input").val() == "") {
                //$(parentBx).find(".customsel").eq(selectBx[index]).addClass("chetan");
                $(selVal).eq(valChange).find("input").siblings(".parsley-errors-list").addClass("filled filledPosition").html("<li>Required</li>");
            } else {
                $(selVal).eq(valChange).find("input").siblings(".parsley-errors-list").children("li").remove();
            }
        }
    }

    //=====form action stop====//   
    $(document).on('click', '.formBtnAction', function() {
        var indexPart = [];
        $('.customsel').each(function(index) {
            if (($(this).find("input").prop("disabled") == false) && ($(this).find("input").val() == "")) {
                $('.formBtnAction').attr("type", "button");
                $("form").parsley().validate();
                indexPart.push(index);
            }
        });
        if (indexPart.length == 0) {
            $('.formBtnAction').attr("type", "submit");
        }
    });
    //=====form action stop====//   

    //==500 character validation==//
    $('.chacterValidtion').unbind('keyup change input paste').bind('keyup change input paste', function(e) {

        var $this = $(this);
        var val = $this.val();
        var valLength = val.length;
        var maxCount = $this.attr('maxlength');
        if (valLength > maxCount) {
            $this.val($this.val().substring(0, maxCount));
        }
    });
    //==500 character validation==//


    $('#createProfileForm').on('submit', function() {
        var currentBtn = $(this);
        if (currentBtn.parsley().isValid()) {

            createProfile();

        }

    });


    //form button disabled//
    $('.txtBtnDisable').on('focus', function() {

        $(this).closest("form").find("button").attr("disabled", false);

    })


    $(document).on('keyup', '.phone-number', function(e) {
        var x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
        e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
    });
    //----- customValidation-----//
    $('.DynamicAddder').on("click", dynamicBox);
    $(document).on("click", ".deleteCard", deleteBox);
    $(document).on('change', '.masterBox .ckBox input[type="checkbox"]', checkBoxValidation);


});
