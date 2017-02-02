$(function () {
    "use strict";
    var selVal, selLen;
    $(".addProfileBtn").addClass("DynamicAddder");
    var replicaBoxOne, boxLength, firstBox, secondBox, currentFind, dynamicCount;
    boxLength = 0, dynamicCount = 0;
    function totalCount() {
        boxLength = $('.replicaBox').length;
        $('.DynamicAddder').html(`<span class="icon icon-plus"></span> Add total of ${boxLength} locations`);
        if (boxLength >= 3) {
            $('.addProfileBtn').removeClass('DynamicAddder');
        }
    }
    function addBox() {
        if ($(this).hasClass('DynamicAddder')) {
            replicaBoxOne = '<div class="resp-tabs-container commonBox replicaBox profilePadding cboxbottom masterBox"><div class="descriptionBox"><div class="dropdown icon-upload-ctn1"> <span class="icon-upload-detail dropdown-toggle" data-toggle="dropdown" aria-expanded="true"></span> <ul class="actions text-left dropdown-menu"> <li ><span class="gbllist iconEdit"><i class="icon icon-edit "></i> Edit</span></li><li><span class="gbllist iconDel"><i class="icon icon-deleteicon"></i> Delete</span></li></ul> </div><div class="descriptionBoxInner"><div class="viewProfileRightCard pd-b-25"><div class="detailTitleBlock"> <h5>OFFICE DETAILS</h5> </div><h6>Dental Office Type</h6><p>Dental Office Type</p></div><div class="viewProfileRightCard pd-b-25"><h6>Dental Office Address</h6><p>Smiley Care, 910 South 17th Street, Newark, New York 07108, USA</p></div><div class="viewProfileRightCard pd-b-25"><h6>Phone Number</h6><p>(415) 200 - 2356</p></div><div class="viewProfileRightCard pd-b-25"><h6>Working Hours</h6><p>Monday : 9am to 6pm</p><p>Monday : 9am to 6pm</p><p>Monday : 9am to 6pm</p></div><div class="viewProfileRightCard pd-b-25"><h6>Office Location Information</h6><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quorum sine causa fieri nihil putandum est. Potius inflammat, ut coercendi magis quam dedocendi esse videantur. Duo Reges: constructio interrete. Mihi enim satis est, ipsis non satis. Cuius ad naturam apta ratio vera illa et summa lex a philosophis dicitur. Id est enim, de quo quaerimus. Id quaeris, inquam, in quo, utrum respondero, verses te huc atque illuc necesse est. Sed ad bona praeterita redeamus. Roges enim Aristonem, bonane ei videantur haec: vacuitas doloris, divitiae, valitudo; Nam prius a se poterit quisque discedere quam appetitum earum rerum, quae sibi conducant, amittere.</p></div></div></div></div>';
            $('.addReplica').append(replicaBoxOne);
        }
        totalCount();
    }
    function WeekOption() {
        $('.EveryDayCheck').find('input:checkbox').on('change', function () {
            if ($(this).prop("checked")) {
                $(this).parents(".EveryDayCheck").siblings(".allDay").hide();
            } else {
                $(this).parents(".EveryDayCheck").siblings(".allDay").show();
            }
        });
    }
    function DynamicTickName() {
        $(".weekBox:last .ckBox").each(function (index) {
            $(this).children("input:checkbox").attr("id", "dynamicTest" + dynamicCount);
            $(this).children("label").attr("for", "dynamicTest" + dynamicCount);
            dynamicCount++;
        });
    }
    function firstBoxAdd() {
        $(this).parents('.resp-tabs-container').find('.descriptionBox').hide();
        firstBox = ' <div class="form-group"> <label>Dental Office Name</label> <input value="' + $('#hiddenofficename').val() + '" type="text" class="form-control" data-parsley-required="" data-parsley-required-message="office name required" data-parsley-id="1934"><ul class="parsley-errors-list" id="parsley-id-1934"></ul> </div><div class="form-group"> <label>Dental Office Description</label> <textarea class="form-control txtHeight"  data-parsley-required data-parsley-required-message="office description required"  data-parsley-maxlength="500" data-parsley-maxlength-message="Charcter should be 500" data-parsley-trigger="keyup">' + $('#hiddenofficedesc').val() + '</textarea><ul class="parsley-errors-list" id="parsley-id-6436"></ul> </div><div class="pull-right text-right"><button type="button" class="btn btn-link mr-r-10 cancelled" style="font-weight:500" >Cancel</button><button type="submit" class="btn btn-primary pd-l-40 pd-r-40">Save</button></div><br><br>';
        $('.resp-tabs-container').first().append(`<div class="descriptionBox1">${firstBox}</div>`);
    }
    function boxdel() {
        boxLength = $('.replicaBox').length;
        $('.DynamicAddder').html(`<span class="icon icon-plus"></span> Add total of ${boxLength} locations`);
    }
    function editBox() {
        var editId = $(this).closest(".descriptionBox").find("#hiddenEditId").val();
        console.log(editId);
        currentFind = $(this).parents('.descriptionBox');
        //===old editbox close===//
        $('.masterBox').addClass('onBox');
        $(this).closest('.masterBox').removeClass("onBox");
        $('.onBox').find('.descriptionBox1').hide();
        $('.onBox').find('.descriptionBox').show();
        //===old editbox close===//
        $(this).parents('.resp-tabs-container').find('.descriptionBox').hide();
        if ($(currentFind).find('.formField').length < 1) {
            var checkedEvery, checkedMon, checkedTue, checkedWed, checkedThu, checkedFri, checkedSat, checkedSun;
            if ($('#hiddeneveryday' + editId).val() == 1) {
                checkedEvery = 'checked';
            }
            if ($('#hiddenmonday' + editId).val() == 1) {
                checkedMon = 'checked';
            }
            if ($('#hiddentuesday' + editId).val() == 1) {
                checkedTue = 'checked';
            }
            if ($('#hiddenwednesday' + editId).val() == 1) {
                checkedWed = 'checked';
            }
            if ($('#hiddenthursday' + editId).val() == 1) {
                checkedThu = 'checked';
            }
            if ($('#hiddenfriday' + editId).val() == 1) {
                checkedFri = 'checked';
            }
            if ($('#hiddensaturday' + editId).val() == 1) {
                checkedSat = 'checked';
            }
            if ($('#hiddensunday' + editId).val() == 1) {
                checkedSun = 'checked';
            }

            var officeTypes = $.parseJSON($('#hiddenofficeTypesJson').val());
            var options = "";
            $.each(officeTypes, function (index, value) {
                options += "<option value='" + value.id + "'>" + value.officetype_name + "</option>";
            });

            var hiddenFields = '<input value="' + $('#hiddenzipcode' + editId).val() + '" type="text" id="postal_code' + editId + '" name="postal_code" ><input value="' + $('#hiddenlat' + editId).val() + '" type="text" name="lat" id="lat' + editId + '"><input value="' + $('#hiddenlng' + editId).val() + '" type="text" name="lng" id="lng' + editId + '"><input value="' + $('#hiddenofficeaddress' + editId).val() + '" type="text" name="full_address" id="full_address' + editId + '">';
            secondBox = hiddenFields + '<div class=formField><div class=form-group><div class=detailTitleBlock><h5>OFFICE DETAILS</h5></div><label>Dental Office Type</label><div class=slt><select name="officeType[]" class=ddlCars data-parsley-required data-parsley-required-message=" required"multiple>'+options+' </select></div></div><div class=form-group><label>Dental Office Address</label><input value="' + $('#hiddenofficeaddress' + editId).val() + '" class=form-control data-parsley-required data-parsley-required-message="office address required"placeholder="Office name, Street, City, Zip Code and Country"></div><div class=form-group><label>Phone Number</label><input value="' + $('#hiddenphone' + editId).val() + '" class="form-control phone-number"data-parsley-required data-parsley-required-message="phone number required"data-parsley-minlength=14 data-parsley-minlength-message="phone number should be 10 digit"data-parsley-trigger=keyup></div><div class=form-group><label>Working Hours</label><div class=weekBox><div class="dayBox row EveryDayCheck"><div class=col-sm-4><p class=ckBox><input id=test2 type=checkbox ' + checkedEvery + '><label class=ckColor for=test2>Everyday</label></div><div class=col-sm-4><div class="customsel date input-group datetimepicker1"><input value="' + $('#hiddeneverystart' + editId).val() + '" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div><div class=col-sm-4><div class="customsel date input-group datetimepicker2"><input value="' + $('#hiddeneveryend' + editId).val() + '" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div></div><div class=allDay><div class="dayBox row"><div class=col-sm-4><p class=ckBox><input id=mon  type=checkbox ' + checkedMon + '><label class=ckColor for=mon>Monday</label></div><div class=col-sm-4><div class="customsel date input-group datetimepicker1"><input value="' + $('#hiddenmonstart' + editId).val() + '" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div><div class=col-sm-4><div class="customsel date input-group datetimepicker2"><input value="' + $('#hiddenmonend' + editId).val() + '" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div></div><div class="dayBox row"><div class=col-sm-4><p class=ckBox><input id=tue type=checkbox ' + checkedTue + '><label class=ckColor for=tue>Tuesday</label></div><div class=col-sm-4><div class="customsel date input-group datetimepicker1"><input value="' + $('#hiddentuestart' + editId).val() + '" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div><div class=col-sm-4><div class="customsel date input-group datetimepicker2"><input value="' + $('#hiddentueend' + editId).val() + '" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div></div><div class="dayBox row"><div class=col-sm-4><p class=ckBox><input id=wed type=checkbox ' + checkedWed + '><label class=ckColor for=wed>Wednesday</label></div><div class=col-sm-4><div class="customsel date input-group datetimepicker1"><input value="' + $('#hiddenwedstart' + editId).val() + '" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div><div class=col-sm-4><div class="customsel date input-group datetimepicker2"><input value="' + $('#hiddenwedend' + editId).val() + '" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div></div><div class="dayBox row"><div class=col-sm-4><p class=ckBox><input id=thu type=checkbox ' + checkedThu + '><label class=ckColor for=thu>Thursday</label></div><div class=col-sm-4><div class="customsel date input-group datetimepicker1"><input value="' + $('#hiddenthustart' + editId).val() + '" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div><div class=col-sm-4><div class="customsel date input-group datetimepicker2"><input value="' + $('#hiddenthuend' + editId).val() + '" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div></div><div class="dayBox row"><div class=col-sm-4><p class=ckBox><input id=fri type=checkbox ' + checkedFri + '><label class=ckColor for=fri>Friday</label></div><div class=col-sm-4><div class="customsel date input-group datetimepicker1"><input value="' + $('#hiddenfristart' + editId).val() + '" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div><div class=col-sm-4><div class="customsel date input-group datetimepicker2"><input value="' + $('#hiddenfriend' + editId).val() + '" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div></div><div class="dayBox row"><div class=col-sm-4><p class=ckBox><input id=sat type=checkbox ' + checkedSat + '><label class=ckColor for=sat>Saturday</label></div><div class=col-sm-4><div class="customsel date input-group datetimepicker1"><input value="' + $('#hiddensatstart' + editId).val() + '" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div><div class=col-sm-4><div class="customsel date input-group datetimepicker2"><input value="' + $('#hiddensatend' + editId).val() + '" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div></div><div class="dayBox row"><div class=col-sm-4><p class=ckBox><input id=sun type=checkbox ' + checkedSun + '><label class=ckColor for=sun>Sunday</label></div><div class=col-sm-4><div class="customsel date input-group datetimepicker1"><input value="' + $('#hiddensunstart' + editId).val() + '" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div><div class=col-sm-4><div class="customsel date input-group datetimepicker2"><input value="' + $('#hiddensunend' + editId).val() + '" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div></div></div></div></div><div class=form-group><label>Office Location Information <i class=optional>(Optional)</i></label><textarea class="form-control txtHeight"data-parsley-maxlength=100 data-parsley-maxlength-message="Charcter should be 500"data-parsley-required data-parsley-required-message="location information required">' + $('#hiddenlocation' + editId).val() + '</textarea></div><div class="pull-right text-right"><button type="button" class="btn btn-link mr-r-10 cancelled" style="font-weight:500">Cancel</button><button class="btn btn-primary pd-l-40 pd-r-40 formBtnAction"type=submit>Save</button></div><br><br></div>';
            boxdel()
//		currentFind.append(secondBox);
            $(this).closest('.resp-tabs-container').first().append(`<div class="descriptionBox1">${secondBox}</div>`);
            $('.ddlCars').multiselect({
                numberDisplayed: 3,
            });
            WeekOption()
            $(".dropCheck").find("input").after("<div></div>");
        }
        DynamicTickName()
    }
    function checkBoxValidation() {
        var parentBx = $(this).parents(".dayBox");
        if ($(this).prop("checked") == true) {
            selVal = parentBx.find(".customsel");
            selLen = parentBx.find(".customsel").length;
            $(selVal).find("input").prop("disabled", false);
            blackValueCheck()
            //-----datePicker---//
            var $startTime1 = $('.datetimepicker1');
            var $endTime1 = $('.datetimepicker2');
            $startTime1.datetimepicker({
                format: 'hh:mm A',
//		defaultDate: new Date(),
                //ignoreReadonly: true,
                minDate: moment().startOf('day'),
                maxDate: moment().endOf('day')
            });
            $endTime1.datetimepicker({
                format: 'hh:mm A',
//		defaultDate: $startTime1.data("DateTimePicker").date().add(1, 'minutes'),
//		useCurrent: false,
                //ignoreReadonly: true,
                minDate: moment().startOf('day'),
                maxDate: moment().endOf('day')
            });
            $('.datetimepicker1').on("dp.change", function () {
                $(this).addClass("chetan");
                var date = $(this).data('date');
                $(this).closest(".row").find('.datetimepicker2').data('DateTimePicker').minDate(date);
                console.log(date);
            });
            $('.datetimepicker2').on("dp.change", function () {
                var date = $(this).data('date');
                $(this).closest(".row").find('.datetimepicker1').data('DateTimePicker').maxDate(date);
                console.log(date);
            });
            //-----datePicker---//
            $(".customsel").on("dp.change", function (e) {
                //	blackValueCheck()
                if ($(this).find("input").prop("disabled") == false) {
                    if ($(this).find("input").val() == "") {
                        $(this).find("input").siblings(".parsley-errors-list").html("<li>Field Required</li>");
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
                $(selVal).eq(valChange).find("input").siblings(".parsley-errors-list").addClass("filled filledPosition").html("<li>Field Required</li>");
            } else {
                $(selVal).eq(valChange).find("input").siblings(".parsley-errors-list").children("li").remove();
            }
        }
    }
    //=====form action stop====//	
    $(document).on('click', '.formBtnAction', function () {
        var indexPart = [];
        $('.customsel').each(function (index) {
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
    //=====phone number====//	
    $(document).on('keyup', '.phone-number', function (e) {
        var x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
        e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
    });

//=====phone number====//	
    function deleteItem() {
        currentFind = $(this);
        currentFind.parents('.resp-tabs-container').remove();
        $('.addProfileBtn').addClass('DynamicAddder');
        boxdel()
    }
//=====	Box Cancel=====//

    function cancelledBox() {
        var current = $(this).closest(".resp-tabs-container");
        current.find(".descriptionBox").show();
        current.find(".descriptionBox1").hide();
    }

    //=====	Box Cancel=====//
    $('.addProfileBtn,.iconFirstEdit').on('click', addBox);
    $('.iconFirstEdit').on('click', firstBoxAdd);
    $(document).on('click', '.iconEdit', editBox)
    $(document).on('click', '.iconDel', deleteItem);
    $(document).on('change', '.masterBox .ckBox input[type="checkbox"]', checkBoxValidation);
    $(document).on('click', '.cancelled', cancelledBox)
});