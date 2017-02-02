$(function () {
    "use strict";
    var selVal, selLen;
    $(".addProfileBtn").addClass("DynamicAddder");
	var globalMasterLen=$('.masterBox').length;
	$('.addProfileBtn').append(`You can add upto ${3-globalMasterLen} more location`);
    var replicaBoxOne, boxLength, firstBox, secondBox, currentFind, dynamicCount;
    boxLength = 0, dynamicCount = 0;
    function totalCount() {
        boxLength = $('.masterBox').length;
	
        $('.DynamicAddder').html(`<span class='icon icon-plus'></span>You can add upto ${3-boxLength} more locations`);
        if (boxLength >= 3) {
            $('.addProfileBtn').removeClass('DynamicAddder');

				 $(".addProfileBtn").empty();
        }
    }
    function addBox() {
        if ($(this).hasClass('DynamicAddder')) {
            replicaBoxOne = '<div class="resp-tabs-container commonBox replicaBox profilePadding cboxbottom masterBox"><p class="deleteCard iconDel pull-right"><span class="icon icon-deleteicon"></span>Delete</p><div class="formField"><div class="form-group"><div class="detailTitleBlock"><h5>OFFICE DETAILS</h5></div><label>Dental Office Type</label><div class="slt"><span class="multiselect-native-select"><select class="ddlCars" data-parsley-required="" data-parsley-required-message="Required" multiple=""><option value="Accord">Accord</option><option value="Duster">Duster</option><option value="Esteem">Esteem</option><option value="Fiero">Fiero</option><option value="Lancer">Lancer</option><option value="Phantom">Phantom</option></select><div class="btn-group buttonWidth"><button type="button" class="multiselect dropdown-toggle btn btn-default" data-toggle="dropdown" title="select"><span class="multiselect-selected-text">select</span> <b class="caret caretModify"></b></button><ul class="multiselect-container dropdown-menu "><li><a tabindex="0" class="dropCheck"><label class="checkbox"><input type="checkbox" value="Accord"><div></div> Accord</label></a></li><li><a tabindex="0" class="dropCheck"><label class="checkbox"><input type="checkbox" value="Duster"><div></div> Duster</label></a></li><li><a tabindex="0" class="dropCheck"><label class="checkbox"><input type="checkbox" value="Esteem"><div></div> Esteem</label></a></li><li><a tabindex="0" class="dropCheck"><label class="checkbox"><input type="checkbox" value="Fiero"><div></div> Fiero</label></a></li><li><a tabindex="0" class="dropCheck"><label class="checkbox"><input type="checkbox" value="Lancer"><div></div> Lancer</label></a></li><li><a tabindex="0" class="dropCheck"><label class="checkbox"><input type="checkbox" value="Phantom"><div></div> Phantom</label></a></li></ul></div></span></div></div><div class="form-group"><label>Dental Office Address</label><input class="form-control" data-parsley-required="" data-parsley-required-message="Required" placeholder="Office name, Street, City, Zip Code and Country"></div><div class="form-group"><label>Phone Number</label><input class="form-control phone-number" data-parsley-required="" data-parsley-required-message="Required" ></div><div class="form-group"><label>Working Hours</label><div class="weekBox"><div class="dayBox row EveryDayCheck"><div class="col-sm-4"><p class="ckBox"><input id="dynamicTest0" type="checkbox"><label class="ckColor" for="dynamicTest0">Everyday</label></p></div><div class="col-sm-4"><div class="customsel date input-group datetimepicker1"><input class="form-control" disabled=""><ul class="parsley-errors-list"></ul><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div></div><div class="col-sm-4"><div class="customsel date input-group datetimepicker2"><input class="form-control" disabled=""><ul class="parsley-errors-list"></ul><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div></div></div><div class="allDay"><div class="dayBox row"><div class="col-sm-4"><p class="ckBox"><input id="dynamicTest1" type="checkbox"><label class="ckColor" for="dynamicTest1">Monday</label></p></div><div class="col-sm-4"><div class="customsel date input-group datetimepicker1"><input class="form-control" disabled=""><ul class="parsley-errors-list"></ul><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div></div><div class="col-sm-4"><div class="customsel date input-group datetimepicker2"><input class="form-control" disabled=""><ul class="parsley-errors-list"></ul><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div></div></div><div class="dayBox row"><div class="col-sm-4"><p class="ckBox"><input id="dynamicTest2" type="checkbox"><label class="ckColor" for="dynamicTest2">Tuesday</label></p></div><div class="col-sm-4"><div class="customsel date input-group datetimepicker1"><input class="form-control" disabled=""><ul class="parsley-errors-list"></ul><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div></div><div class="col-sm-4"><div class="customsel date input-group datetimepicker2"><input class="form-control" disabled=""><ul class="parsley-errors-list"></ul><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div></div></div><div class="dayBox row"><div class="col-sm-4"><p class="ckBox"><input id="dynamicTest3" type="checkbox"><label class="ckColor" for="dynamicTest3">Wednesday</label></p></div><div class="col-sm-4"><div class="customsel date input-group datetimepicker1"><input class="form-control" disabled=""><ul class="parsley-errors-list"></ul><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div></div><div class="col-sm-4"><div class="customsel date input-group datetimepicker2"><input class="form-control" disabled=""><ul class="parsley-errors-list"></ul><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div></div></div><div class="dayBox row"><div class="col-sm-4"><p class="ckBox"><input id="dynamicTest4" type="checkbox"><label class="ckColor" for="dynamicTest4">Thursday</label></p></div><div class="col-sm-4"><div class="customsel date input-group datetimepicker1"><input class="form-control" disabled=""><ul class="parsley-errors-list"></ul><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div></div><div class="col-sm-4"><div class="customsel date input-group datetimepicker2"><input class="form-control" disabled=""><ul class="parsley-errors-list"></ul><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div></div></div><div class="dayBox row"><div class="col-sm-4"><p class="ckBox"><input id="dynamicTest5" type="checkbox"><label class="ckColor" for="dynamicTest5">Friday</label></p></div><div class="col-sm-4"><div class="customsel date input-group datetimepicker1"><input class="form-control" disabled=""><ul class="parsley-errors-list"></ul><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div></div><div class="col-sm-4"><div class="customsel date input-group datetimepicker2"><input class="form-control" disabled=""><ul class="parsley-errors-list"></ul><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div></div></div><div class="dayBox row"><div class="col-sm-4"><p class="ckBox"><input id="dynamicTest6" type="checkbox"><label class="ckColor" for="dynamicTest6">Saturday</label></p></div><div class="col-sm-4"><div class="customsel date input-group datetimepicker1"><input class="form-control" disabled=""><ul class="parsley-errors-list"></ul><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div></div><div class="col-sm-4"><div class="customsel date input-group datetimepicker2"><input class="form-control" disabled=""><ul class="parsley-errors-list"></ul><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div></div></div><div class="dayBox row"><div class="col-sm-4"><p class="ckBox"><input id="dynamicTest7" type="checkbox"><label class="ckColor" for="dynamicTest7">Sunday</label></p></div><div class="col-sm-4"><div class="customsel date input-group datetimepicker1"><input class="form-control" disabled=""><ul class="parsley-errors-list"></ul><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div></div><div class="col-sm-4"><div class="customsel date input-group datetimepicker2"><input class="form-control" disabled=""><ul class="parsley-errors-list"></ul><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div></div></div></div></div></div><div class="form-group"><label>Office Location Information <i class="optional">(Optional)</i></label><textarea class="form-control txtHeight" data-parsley-maxlength="100" data-parsley-maxlength-message="Charcter should be 500" data-parsley-required="" data-parsley-required-message="Required"></textarea></div><div class="pull-right text-right"><button class="btn btn-primary pd-l-40 pd-r-40 formBtnAction" type="submit">Save</button></div><br><br></div></div>';
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
        firstBox = ' <div class="form-group"> <label>Dental Office Name</label> <input value="'+$('#hiddenofficename').val()+'" type="text" class="form-control" data-parsley-required="" data-parsley-required-message="Required" data-parsley-id="1934"><ul class="parsley-errors-list" id="parsley-id-1934"></ul> </div><div class="form-group"> <label>Dental Office Description</label> <textarea class="form-control txtHeight"  data-parsley-required data-parsley-required-message="Required"  data-parsley-maxlength="500" data-parsley-maxlength-message="Charcter should be 500" data-parsley-trigger="keyup">'+$('#hiddenofficedesc').val()+'</textarea><ul class="parsley-errors-list" id="parsley-id-6436"></ul> </div><div class="pull-right text-right"><button type="button" class="btn btn-link mr-r-10 cancelled" style="font-weight:500" >Cancel</button><button type="submit" class="btn btn-primary pd-l-40 pd-r-40">Save</button></div><br><br>';
        $('.resp-tabs-container').first().append(`<div class="descriptionBox1">${firstBox}</div>`);
    }
    function boxdel() {
        boxLength = $('.masterBox').length;
        $('.DynamicAddder').html(`<span class="icon icon-plus"></span> You can add upto ${3-boxLength} more locations`);
    }
    function editBox() {
        var editId =119;
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
            var hiddenFields = '<input value="'+$('#hiddenzipcode'+editId).val()+'" type="text" id="postal_code'+editId+'" name="postal_code" ><input value="'+$('#hiddenlat'+editId).val()+'" type="text" name="lat" id="lat'+editId+'"><input value="'+$('#hiddenlng'+editId).val()+'" type="text" name="lng" id="lng'+editId+'"><input value="'+$('#hiddenofficeaddress'+editId).val()+'" type="text" name="full_address" id="full_address'+editId+'">';
            secondBox = hiddenFields+'<div class=formField><div class=form-group><div class=detailTitleBlock><h5>OFFICE DETAILS</h5></div><label>Dental Office Type</label><div class=slt><select class=ddlCars data-parsley-required data-parsley-required-message="required"multiple><option value=Accord>Accord<option value=Duster>Duster<option value=Esteem>Esteem<option value=Fiero>Fiero<option value=Lancer>Lancer<option value=Phantom>Phantom</select></div></div><div class=form-group><label>Dental Office Address</label><input value="'+$('#hiddenofficeaddress'+editId).val()+'" class=form-control data-parsley-required data-parsley-required-message="required" data-parsley-trigger="keyup" placeholder="Office name, Street, City, Zip Code and Country"></div><div class=form-group><label>Phone Number</label><input value="'+$('#hiddenphone'+editId).val()+'" class="form-control phone-number"data-parsley-required data-parsley-required-message="Required" data-parsley-trigger=keyup></div><div class=form-group><label>Working Hours</label><div class=weekBox><div class="dayBox row EveryDayCheck"><div class=col-sm-4><p class=ckBox><input id=test2 type=checkbox><label class=ckColor for=test2>Everyday</label></div><div class=col-sm-4><div class="customsel date input-group datetimepicker1"><input value="'+$('#hiddeneverystart'+editId).val()+'" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div><div class=col-sm-4><div class="customsel date input-group datetimepicker2"><input value="'+$('#hiddeneveryend'+editId).val()+'" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div></div><div class=allDay><div class="dayBox row"><div class=col-sm-4><p class=ckBox><input id=mon type=checkbox ><label class=ckColor for=mon>Monday</label></div><div class=col-sm-4><div class="customsel date input-group datetimepicker1"><input value="'+$('#hiddenmonstart'+editId).val()+'" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div><div class=col-sm-4><div class="customsel date input-group datetimepicker2"><input value="'+$('#hiddenmonend'+editId).val()+'" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div></div><div class="dayBox row"><div class=col-sm-4><p class=ckBox><input id=tue type=checkbox><label class=ckColor for=tue>Tuesday</label></div><div class=col-sm-4><div class="customsel date input-group datetimepicker1"><input value="'+$('#hiddentuestart'+editId).val()+'" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div><div class=col-sm-4><div class="customsel date input-group datetimepicker2"><input value="'+$('#hiddentueend'+editId).val()+'" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div></div><div class="dayBox row"><div class=col-sm-4><p class=ckBox><input id=wed type=checkbox><label class=ckColor for=wed>Wednesday</label></div><div class=col-sm-4><div class="customsel date input-group datetimepicker1"><input value="'+$('#hiddenwedstart'+editId).val()+'" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div><div class=col-sm-4><div class="customsel date input-group datetimepicker2"><input value="'+$('#hiddenwedend'+editId).val()+'" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div></div><div class="dayBox row"><div class=col-sm-4><p class=ckBox><input id=thu type=checkbox><label class=ckColor for=thu>Thursday</label></div><div class=col-sm-4><div class="customsel date input-group datetimepicker1"><input value="'+$('#hiddenthustart'+editId).val()+'" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div><div class=col-sm-4><div class="customsel date input-group datetimepicker2"><input value="'+$('#hiddenthuend'+editId).val()+'" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div></div><div class="dayBox row"><div class=col-sm-4><p class=ckBox><input id=fri type=checkbox><label class=ckColor for=fri>Friday</label></div><div class=col-sm-4><div class="customsel date input-group datetimepicker1"><input value="'+$('#hiddenfristart'+editId).val()+'" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div><div class=col-sm-4><div class="customsel date input-group datetimepicker2"><input value="'+$('#hiddenfriend'+editId).val()+'" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div></div><div class="dayBox row"><div class=col-sm-4><p class=ckBox><input id=sat type=checkbox><label class=ckColor for=sat>Saturday</label></div><div class=col-sm-4><div class="customsel date input-group datetimepicker1"><input value="'+$('#hiddensatstart'+editId).val()+'" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div><div class=col-sm-4><div class="customsel date input-group datetimepicker2"><input value="'+$('#hiddensatend'+editId).val()+'" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div></div><div class="dayBox row"><div class=col-sm-4><p class=ckBox><input id=sun type=checkbox><label class=ckColor for=sun>Sunday</label></div><div class=col-sm-4><div class="customsel date input-group datetimepicker1"><input value="'+$('#hiddensunstart'+editId).val()+'" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div><div class=col-sm-4><div class="customsel date input-group datetimepicker2"><input value="'+$('#hiddensunend'+editId).val()+'" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div></div></div></div></div><div class=form-group><label>Office Location Information <i class=optional>(Optional)</i></label><textarea class="form-control txtHeight"data-parsley-maxlength=100 data-parsley-maxlength-message="Charcter should be 500"data-parsley-required data-parsley-required-message="Required">'+$('#hiddenlocation'+editId).val()+'</textarea></div><div class="pull-right text-right"><button type="button" class="btn btn-link mr-r-10 cancelled" style="font-weight:500">Cancel</button><button class="btn btn-primary pd-l-40 pd-r-40 formBtnAction"type=submit>Save</button></div><br><br></div>';
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
				'allowInputToggle' : true,
//		defaultDate: new Date(),
                //ignoreReadonly: true,
                minDate: moment().startOf('day'),
                maxDate: moment().endOf('day')
            });
            $endTime1.datetimepicker({
                format: 'hh:mm A',
				'allowInputToggle' : true,
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
        boxdel();
		

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