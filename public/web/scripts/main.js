$(document).ready(function(){
	//floating label js
	$('.floating-label .form-control').on('focus blur', function (e) {
		$(this).parents('.form-group').toggleClass('focused', (e.type === 'focus' || this.value.length > 0));
	}).trigger('blur')

	

});
//Onboarding modal js
$(window).load(function(){
	$('#onboardView').modal({
		show: true,
		backdrop: 'static',
		keyboard: false
	});
});
