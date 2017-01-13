$(function(){
	"use strict";
	  var dynamicCount=0;
	var dynamicLength;
$(".dropCheck").find("input").after("<div></div>");
function dropDownDynamic(){
	
		$('.ddlCars').multiselect({ 
         numberDisplayed: 3,
		  });
}
	
	
	function lengthRestriction(event){
		
dynamicLength=$(".masterBox").length;
		
		
$(".DynamicAdd").html("<span class='icon icon-plus'></span> Add total of "+ dynamicLength +" locations")	
	if(dynamicLength>=3){
			
$(".addBtn").removeClass("DynamicAdd");
	
			}
}
	
	
	
	
function DynamicTickName(){
	 $(".weekBox:last .ckBox").each(function(index){   
		  $(this).children("input:checkbox").attr("id","dynamicTest"+dynamicCount);
		   $(this).children("label").attr("for","dynamicTest"+dynamicCount);
		   dynamicCount++;
		   });
}	
	


	function WeekOption(){
	$('.EveryDayCheck').find('input:checkbox').on('change',function(){

if($(this).prop("checked")){
	
$(this).parents(".EveryDayCheck").siblings(".allDay").hide();
	
}
	
	else{
$(this).parents(".EveryDayCheck").siblings(".allDay").show();
		
	}
	
});	
	}	

		


	
	
	
	function dynamicBox(){
		var masterCLone,dynamicDiv,currentEvent;	
if($(this).hasClass('DynamicAdd')){
		currentEvent=$(this);
dynamicDiv=$(".profieBox").append("<div class='commonBox cboxbottom masterBox'><div>");
//masterCLone = $(".masterBox:first").children().clone()
masterCLone ='<p class="deleteCard pull-right"><span class="icon icon-deleteicon"></span>Delete</p><div class="form-group"> <div class="detailTitleBlock"> <h5>OFFICE DETAILS</h5> </div> <label>Dental Office Type</label> <div class="slt"> <select class="ddlCars" multiple="multiple" data-parsley-required data-parsley-required-message="office type required"> <option value="Accord">Accord</option> <option value="Duster">Duster</option> <option value="Esteem">Esteem</option> <option value="Fiero">Fiero</option> <option value="Lancer">Lancer</option> <option value="Phantom">Phantom</option> </select> </div></div><div class="form-group"> <label>Dental Office Address</label> <input type="text" class="form-control" placeholder="Office name, Street, City, Zip Code and Country" data-parsley-required data-parsley-required-message="office address required"> </div><div class="form-group"> <label>Phone Number</label> <input type="text" class="form-control" data-parsley-required data-parsley-required-message="phone number required" data-parsley-maxlength="10" data-parsley-maxlength-message="number should be 10" data-parsley-trigger="keyup" data-parsley-type="digits"> </div><div class="allCheckBox"> <div class="form-group"> <label>Working Hours</label> <div class="weekBox"> <div class="row dayBox EveryDayCheck"> <div class="col-sm-4"> <p class="ckBox"> <input type="checkbox" id="test2" /> <label for="test2" class="ckColor"> Everyday</label> </p> </div> <div class="col-sm-4"> <input type="text" class="form-control" placeholder="Opening Hours" data-parsley-required data-parsley-required-message="opening hours required"> </div> <div class="col-sm-4"> <input type="text" class="form-control" placeholder="Closing Hours" data-parsley-required data-parsley-required-message="closing hours required"> </div> </div><div class="allDay"> <div class="row dayBox"> <div class="col-sm-4"> <p class="ckBox"> <input type="checkbox" id="mon" /> <label for="mon" class="ckColor"> Monday</label> </p> </div> <div class="col-sm-4"> <input type="text" class="form-control" placeholder="Opening Hours" data-parsley-required data-parsley-required-message="opening hours required"> </div> <div class="col-sm-4"> <input type="text" class="form-control" placeholder="Closing Hours" data-parsley-required data-parsley-required-message="closing hours required"> </div> </div> <div class="row dayBox"> <div class="col-sm-4"> <p class="ckBox"> <input type="checkbox" id="tue" /> <label for="tue" class="ckColor"> Tuesday</label> </p> </div> <div class="col-sm-4"> <input type="text" class="form-control" placeholder="Opening Hours" data-parsley-required data-parsley-required-message="opening hours required"> </div> <div class="col-sm-4"> <input type="text" class="form-control" placeholder="Closing Hours" data-parsley-required data-parsley-required-message="closing hours required"> </div> </div> <div class="row dayBox"> <div class="col-sm-4"> <p class="ckBox"> <input type="checkbox" id="wed" /> <label for="wed" class="ckColor"> Wednesday</label> </p> </div> <div class="col-sm-4"> <input type="text" class="form-control" placeholder="Opening Hours" data-parsley-required data-parsley-required-message="opening hours required"> </div> <div class="col-sm-4"> <input type="text" class="form-control" placeholder="Closing Hours" data-parsley-required data-parsley-required-message="closing hours required"> </div> </div> <div class="row dayBox"> <div class="col-sm-4"> <p class="ckBox"> <input type="checkbox" id="thu" /> <label for="thu" class="ckColor"> Thursday</label> </p> </div> <div class="col-sm-4"> <input type="text" class="form-control" placeholder="Opening Hours" data-parsley-required data-parsley-required-message="opening hours required"> </div> <div class="col-sm-4"> <input type="text" class="form-control" placeholder="Closing Hours" data-parsley-required data-parsley-required-message="closing hours required"> </div> </div> <div class="row dayBox"> <div class="col-sm-4"> <p class="ckBox"> <input type="checkbox" id="fri" /> <label for="fri" class="ckColor"> Friday</label> </p> </div> <div class="col-sm-4"> <input type="text" class="form-control" placeholder="Opening Hours" data-parsley-required data-parsley-required-message="opening hours required"> </div> <div class="col-sm-4"> <input type="text" class="form-control" placeholder="Closing Hours" data-parsley-required data-parsley-required-message="closing hours required"> </div> </div> <div class="row dayBox"> <div class="col-sm-4"> <p class="ckBox"> <input type="checkbox" id="sat" /> <label for="sat" class="ckColor"> Saturday</label> </p> </div> <div class="col-sm-4"> <input type="text" class="form-control" placeholder="Opening Hours" data-parsley-required data-parsley-required-message="opening hours required"> </div> <div class="col-sm-4"> <input type="text" class="form-control" placeholder="Closing Hours" data-parsley-required data-parsley-required-message="closing hours required"> </div> </div> <div class="row dayBox"> <div class="col-sm-4"> <p class="ckBox"> <input type="checkbox" id="sun" /> <label for="sun" class="ckColor"> Sunday</label> </p> </div> <div class="col-sm-4"> <input type="text" class="form-control" placeholder="Opening Hours" data-parsley-required data-parsley-required-message="opening hours required"> </div> <div class="col-sm-4"> <input type="text" class="form-control" placeholder="Closing Hours" data-parsley-required data-parsley-required-message="closing hours required"> </div> </div></div> </div> </div></div><div class="form-group"> <label>Office Location Information (Optional)</label> <textarea class="form-control txtHeight" data-parsley-required data-parsley-required-message="location information required" data-parsley-maxlength="100" data-parsley-maxlength-message="Charcter should be 500"></textarea></div>';
	
	 $(".masterBox:last").html(masterCLone);
	dropDownDynamic()
	$(".masterBox:last").find(".dropCheck input").after("<div></div>");
	DynamicTickName()
WeekOption();

}	
	   else{ console.log("not exists");}		
	
		
		
lengthRestriction();
}

	
	

WeekOption();	
	
		
function deleteBox(){
$(this).parents(".masterBox").remove();
 dynamicLength=$(".masterBox").length;
$(".addBtn").addClass("DynamicAdd");	

$(".DynamicAdd").html("<span class='icon icon-plus'></span> Add total of "+ dynamicLength +" locations");	
	
}	
	
	
	
	$('.DynamicAdd').on("click", dynamicBox);
	$(document).on("click", ".deleteCard" ,deleteBox);
	
});