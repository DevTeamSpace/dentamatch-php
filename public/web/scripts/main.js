$(document).ready(function(){
	//floating label js
	$('.floating-label .form-control').on('focus blur', function (e) {
		$(this).parents('.form-group').toggleClass('focused', (e.type === 'focus' || this.value.length > 0));
	}).trigger('blur')

	
	/*---------view more and view less---------*/

	$('.job-detail li').hide().filter(':lt(1)').show();
	//if($('.job-detail li:eq(1) p').text().length >140){
		$('.job-detail')
		.append('<li><a href="#">View more</a><a href="#" class="view_less">View Less</a></li>')
		.find('li:last')
		.click(function(e){
			e.preventDefault();
			$(this)
			.siblings(':gt(1)')
			.toggle()
			.end()
			.find('a')
			.toggle();


		});
	//}



	$('.date-drop .dropdown-menu li').on('click', function(){

		var  day = $(this).html();

		$('.day-drop').html(day);
	});


	/*---------select drop down----*/

	$('#officeAddress, #jobopening, #dentalofficetype').selectpicker({
		style: 'btn  btn-default'
	});


	/*-------datepicker-------*/
	$('#CoverStartDateOtherPicker').datepicker({
		multidate: true,
		orientation: "top auto",
		autoclose: false,
	}).on("show", function () {
		$('.datepicker').addClass('custom-active');
		$('.custom-active .datepicker-days').find('th.prev').text('<');
		$('.custom-active .datepicker-days').find('th.next').text('>');
		$('.datepicker .datepicker-days .table-condensed thead').find('.choose-dates').parent().remove();
		$('.datepicker .datepicker-days .table-condensed thead').prepend('<tr><th class="choose-dates" colspan="14">Choose Dates</th></tr>');
	});


	$('.full-time-box label').click(function () {
                //$(this).parent().parent().parent().find('input').attr("checked",false);
                $(this).parent().find('input').prop("checked", true);
                getId = $(this).parent().find('input').attr('id');

                if (getId === 'parttime') {
                	$('.job-opening').addClass('hide');
                	$('div.select-days-custom').css('display', 'block');
                	$('#monthSelect').multipleSelect({
                		filter: false,
                		isOpen: true,
                		keepOpen: true,
                		selectAll: false,
                		minWidth: 100
                	}).width(300);
                	$(this).parent().parent().find('button span').addClass('placeholder').text('Select Days');
                } else if (getId === 'temporary') {
                	$('div.select-days-custom').css('display', 'none');
                	$('.job-opening').removeClass('hide');
                	$("#CoverStartDateOtherPicker").datepicker("show");
                } else {
                	$('div.select-days-custom').css('display', 'none');
                	$('.job-opening').addClass('hide');
                }
            });
	$(document).on('click', '.select-days-custom div.ms-drop', function (e) {
		$(this).parent().parent().find('button span').addClass('placeholder').text('Select Days');
	});
	$(document).on('click', '.select-days-custom button', function (event) {
		$(this).find('div').addClass('open');
		$(this).parent().find('.ms-drop ').css('display', 'block');
	});

	/*-----------Add template----------*/
	$('.info-block img').on( "mouseenter", function() {

		$(this).closest('div.mainTemplateBlock').children('div.defaultBlock').addClass('hide');
		$(this).closest('div.mainTemplateBlock').children('div.hoverBlock').removeClass('hide');
		;
	});
	$('.mainTemplateBlock').on( "mouseleave", function() {
		$(this).closest('div.mainTemplateBlock').children('div.defaultBlock').removeClass('hide');
		$(this).closest('div.mainTemplateBlock').children('div.hoverBlock').addClass('hide');

	});
	/*-----------Add template----------*/

/*-----------range slider--------*/
	
	$("#range_slider").slider({ 
		min: 1, 
		max: 20, 
		value: 0, 
		tooltip_position:'bottom',
		
		formatter: function(value) {
			return   value + ' miles ' ;
		}
	});
	/*-----------range slider--------*/

});




//Onboarding modal js
$(window).load(function(){
	$('#onboardView').modal({
		show: true,
		backdrop: 'static',
		keyboard: false
	});


});
