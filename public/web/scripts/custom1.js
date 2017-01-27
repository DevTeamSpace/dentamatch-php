$(function () {
    "use strict";
    var dynamicCount = 0;
    var dynamicLength, selVal, selLen;
    $(".dropCheck").find("input").after("<div></div>");
    function dropDownDynamic() {
        $('.ddlCars').multiselect({
            numberDisplayed: 3,
        });
    }
    function lengthRestriction(event) {
        dynamicLength = $(".masterBox").length;
        $(".DynamicAddder").html("<span class='icon icon-plus'></span> Add total of " + dynamicLength + " locations")
        if (dynamicLength >= 3) {
            $(".addBtn").removeClass("DynamicAddder");
        }
    }
    function DynamicTickName() {
        $(".weekBox:last .ckBox").each(function (index) {
            $(this).children("input:checkbox").attr("id", "dynamicTest" + dynamicCount);
            $(this).children("label").attr("for", "dynamicTest" + dynamicCount);
            dynamicCount++;
        });
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
    function dynamicBox() {
        dynamicLength = $(".masterBox").length;
        var masterCLone, dynamicDiv, currentEvent;
        if ($(this).hasClass('DynamicAddder')) {
            currentEvent = $(this);
            dynamicDiv = $(".profieBox").append("<div class='commonBox cboxbottom masterBox'><div>");
//masterCLone = $(".masterBox:first").children().clone()
            var officeTypes = $.parseJSON($('#officeTypesJson').val());
            var options = "";
            $.each(officeTypes,function(index,value){
                options += "<option value='"+value.id+"'>"+value.officetype_name+"</option>";
            });
            var hiddenFields = '<table id="address" ><tr><td class="label">Street address</td><td class="slimField"><input class="field" id="street_number" disabled="true"></input></td><td class="wideField" colspan="2"><input class="field" id="route"   disabled="true"></input></td></tr><tr><td class="label">City</td><td class="wideField" colspan="3"><input class="field" id="locality" disabled="true"></input></td></tr><tr><td class="label">State</td><td class="slimField"><input class="field" id="administrative_area_level_1" disabled="tr<input class="field" id="postal_code" name="postal_code"  disabled="true"></div> </input></td></tr><tr><td class="label">Country</td><td class="wideField" colspan="1"><input class="field" id="country" disabled="true"></input></td><td></td> </tr></table><input type="text" name="lat'+dynamicLength+'" id="lat'+dynamicLength+'"><input type="text" name="lng'+dynamicLength+'" id="lng'+dynamicLength+'"><input type="text" name="full_address'+dynamicLength+'" id="full_address'+dynamicLength+'">';
            masterCLone = hiddenFields+'<p class="deleteCard pull-right"><span class="icon icon-deleteicon"></span>Delete<div class=form-group><div class=detailTitleBlock><h5>OFFICE DETAILS</h5></div><label>Dental Office Type</label><div class=slt><select name="officeType'+dynamicLength+'[]" class=ddlCars data-parsley-required data-parsley-required-message="office type required"multiple>'+options+' </select></div></div><div class=form-group><label>Dental Office Address</label><input id="autocomplete'+dynamicLength+'" name="officeAddress'+dynamicLength+'" class=form-control data-parsley-required data-parsley-required-message="office address required"placeholder="Office name, Street, City, Zip Code and Country"></div><div class=form-group><label>Phone Number</label><input  id="phoneNumber'+dynamicLength+'" name="phoneNumber'+dynamicLength+'" class=form-control phone-number data-parsley-required data-parsley-required-message="phone number required"data-parsley-maxlength=10 data-parsley-maxlength-message="number should be 10"data-parsley-trigger=keyup data-parsley-type=digits></div><div class=allCheckBox><div class=form-group><label>Working Hours</label><div class=weekBox><div class="dayBox row EveryDayCheck"><div class=col-sm-4><p class=ckBox><input id=test2 type=checkbox name="everyday'+dynamicLength+'" value="1" ><label class=ckColor for=test2>Everyday</label></div><div class=col-sm-4><div class="customsel date input-group datetimepicker1"><input name="everydayStart'+dynamicLength+'" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div><div class=col-sm-4><div class="customsel date input-group datetimepicker2"><input name="everydayEnd'+dynamicLength+'" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div></div><div class=allDay><div class="dayBox row"><div class=col-sm-4><p class=ckBox><input id=mon name="monday'+dynamicLength+'" value="1" type=checkbox><label class=ckColor for=mon>Monday</label></div><div class=col-sm-4><div class="customsel date input-group datetimepicker1"><input name="mondayStart'+dynamicLength+'"class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div><div class=col-sm-4><div class="customsel date input-group datetimepicker2"><input name="mondayEnd'+dynamicLength+'" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div></div><div class="dayBox row"><div class=col-sm-4><p class=ckBox><input id=tue name="tuesday'+dynamicLength+'" value="1" type=checkbox><label class=ckColor for=tue>Tuesday</label></div><div class=col-sm-4><div class="customsel date input-group datetimepicker1"><input name="tuesdayStart'+dynamicLength+'" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div><div class=col-sm-4><div class="customsel date input-group datetimepicker2"><input name="tuesdayEnd'+dynamicLength+'" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div></div><div class="dayBox row"><div class=col-sm-4><p class=ckBox><input id=wed name="wednesday'+dynamicLength+'" value="1" type=checkbox><label class=ckColor for=wed>Wednesday</label></div><div class=col-sm-4><div class="customsel date input-group datetimepicker1"><input name="wednesdayStart'+dynamicLength+'" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div><div class=col-sm-4><div class="customsel date input-group datetimepicker2"><input name="wednesdayEnd'+dynamicLength+'" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div></div><div class="dayBox row"><div class=col-sm-4><p class=ckBox><input id=thu name="thrusday'+dynamicLength+'" value="1" type=checkbox><label class=ckColor for=thu>Thursday</label></div><div class=col-sm-4><div class="customsel date input-group datetimepicker1"><input name="thrusdayStart'+dynamicLength+'" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div><div class=col-sm-4><div class="customsel date input-group datetimepicker2"><input name="thrusdayEnd'+dynamicLength+'" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div></div><div class="dayBox row"><div class=col-sm-4><p class=ckBox><input id=fri name="friday'+dynamicLength+'" value="1" type=checkbox><label class=ckColor for=fri>Friday</label></div><div class=col-sm-4><div class="customsel date input-group datetimepicker1"><input name="fridayStart'+dynamicLength+'" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div><div class=col-sm-4><div class="customsel date input-group datetimepicker2"><input name="fridayEnd'+dynamicLength+'" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div></div><div class="dayBox row"><div class=col-sm-4><p class=ckBox><input id=sat name="saturday'+dynamicLength+'" value="1" type=checkbox><label class=ckColor for=sat>Saturday</label></div><div class=col-sm-4><div class="customsel date input-group datetimepicker1"><input name="saturdayStart'+dynamicLength+'" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div><div class=col-sm-4><div class="customsel date input-group datetimepicker2"><input name="saturdayEnd'+dynamicLength+'" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div></div><div class="dayBox row"><div class=col-sm-4><p class=ckBox><input name="sunday'+dynamicLength+'" value="1" id=sun type=checkbox><label class=ckColor for=sun>Sunday</label></div><div class=col-sm-4><div class="customsel date input-group datetimepicker1"><input name="sundayStart'+dynamicLength+'" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div><div class=col-sm-4><div class="customsel date input-group datetimepicker2"><input name="sundayEnd'+dynamicLength+'" class=form-control disabled><ul class=parsley-errors-list></ul><span class=input-group-addon><span class="glyphicon glyphicon-calendar"></span></span></div></div></div></div></div></div></div><div class=form-group><label>Office Location Information (Optional)</label><textarea name="officeLocation'+dynamicLength+'" class="form-control txtHeight"data-parsley-maxlength=500 data-parsley-maxlength-message="Charcter should be 500"data-parsley-required data-parsley-required-message="location information required"></textarea></div>';

            $(".masterBox:last").html(masterCLone);

            dropDownDynamic()
            $(".masterBox:last").find(".dropCheck input").after("<div></div>");
            DynamicTickName()
            WeekOption();

//-----datePicker---//
            $('.datetimepicker1').datetimepicker({format: 'LT'});
            $('.datetimepicker2').datetimepicker({
                useCurrent: false, //Important! See issue #1075
                format: 'LT'
            });
            $(".datetimepicker1").on("dp.change", function (e) {
                $(this).closest('.row').find('.datetimepicker2').data("DateTimePicker").minDate(e.date);
            });
            $(".datetimepicker2").on("dp.change", function (e) {
                $(this).closest('.row').find('.datetimepicker1').data("DateTimePicker").maxDate(e.date);
            });

            //-----datePicker---//
        } else {
            console.log("not exists");
        }
        lengthRestriction();
    }
    WeekOption();
    function deleteBox() {
        $(this).parents(".masterBox").remove();
        dynamicLength = $(".masterBox").length;
        $(".addBtn").addClass("DynamicAddder");
        $(".DynamicAddder").html("<span class='icon icon-plus'></span> Add total of " + dynamicLength + " locations");
    }
//-----	customValidation-----//
    function checkBoxValidation() {
        var parentBx = $(this).parents(".dayBox");
        if ($(this).prop("checked") == true) {
            selVal = parentBx.find(".customsel");
            selLen = parentBx.find(".customsel").length;
            $(selVal).find("input").prop("disabled", false);
            blackValueCheck()
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
	$(document).on('click','.formBtnAction',function(){	
		var indexPart=[];
		$('.customsel').each(function(index){
			if(($(this).find("input").prop("disabled")==false) && ($(this).find("input").val()=="")){
				$('.formBtnAction').attr("type","button");
				indexPart.push(index);
				}
		});
		if(indexPart.length==0){
			$('.formBtnAction').attr("type","submit");
		}
	});
//=====form action stop====//	
	

$(document).on('keyup','.phone-number', function (e) {
  var x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
  e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
});
    //-----	customValidation-----//
    $('.DynamicAddder').on("click", dynamicBox);
    $(document).on("click", ".deleteCard", deleteBox);
    //$('.masterBox').find('.ckBox').children('input[type="checkbox"]').on('change',checkBoxValidation);
    $(document).on('change', '.masterBox .ckBox input[type="checkbox"]', checkBoxValidation);
//$('.customsel').on("change",changeSelect);
//$(document).on("click",".formBtnAction",checkBtnValidation);


});