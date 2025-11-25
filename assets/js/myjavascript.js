/*------------------
		Game Slider
	--------------------*/
	$('.game-slider').owlCarousel({
		loop: true,
		nav: false,
		dots: true,
		mouseDrag: false,
		animateOut: 'fadeOut',
    	animateIn: 'fadeIn',
		items: 1,
		autoplay: true
	});
	var dot = $('.game-slider .owl-dot');
	dot.each(function() {
		var index = $(this).index() + 1;
		if(index < 10){
			$(this).html('').append(index);
			$(this).append('<span></span>');
		}else{
			$(this).html(index);
			$(this).append('<span>.</span>');
		}
	});
/*------------------
		mobile menu
	--------------------*/
	$('.nav-switch').on('click', function(event) {
		$('.main-menu').slideToggle(800);
		event.preventDefault();
	});
