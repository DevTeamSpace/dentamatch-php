$(document).ready(function(){
	//floating label js
	$('.floating-label .form-control').on('focus blur', function (e) {
		$(this).parents('.form-group').toggleClass('focused', (e.type === 'focus' || this.value.length > 0));
	}).trigger('blur')

	$('body').on('click','.fxd', function(){

		$('body').addClass('fixed-modal-scroll')
	})
	$('.calendar_list').on('click','.close', function(){
		$('body').removeClass('fixed-modal-scroll')
	});

});
//Onboarding modal js
$(window).load(function(){
	$('#onboardView').modal({
		show: true,
		backdrop: 'static',
		keyboard: false
	});


});


/*------Equal height-------*/

equalheight = function(container){

	var currentTallest = 0,
	currentRowStart = 0,
	rowDivs = new Array(),
	$el,
	topPosition = 0;
	$(container).each(function() {

		$el = $(this);
		$($el).height('auto')
		topPostion = $el.position().top;

		if (currentRowStart != topPostion) {
			for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
				rowDivs[currentDiv].height(currentTallest);
			}
     rowDivs.length = 0; // empty the array
     currentRowStart = topPostion;
     currentTallest = $el.height();
     rowDivs.push($el);
 } else {
 	rowDivs.push($el);
 	currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
 }
 for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
 	rowDivs[currentDiv].height(currentTallest);
 }
});
}

$(window).load(function() {
	equalheight('.equal-vertbox');
});


$(window).resize(function(){
	equalheight('.equal-vertbox');
});