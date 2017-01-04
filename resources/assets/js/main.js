$(document).ready(function() {
	
	$('.owl-carousel').owlCarousel({
	  autoPlay: 5000,
	  navigation: true,
	  slideSpeed : 600,
	  singleItem: true,
	  navigationText: ['<i class="ion-ios-arrow-left"></i>','<i class="ion-ios-arrow-right"></i>']
	});


	// Header Carousel
	$('.header_carousel').owlCarousel({
	  singleItem: true,
	  navigation: true,
	  slideSpeed : 800,
	  navigationText: ['<i class="ion-ios-arrow-left"></i>','<i class="ion-ios-arrow-right"></i>']
	});


	// Meal Carousel
	$('.meal_carousel').owlCarousel({
		autoPlay: false,
	  // items : 4,
	  itemsCustom : [
      [0, 1],
      [767, 1],
      [768, 2],
      [1024, 3],
      [1025, 4]
    ],
	  slideSpeed : 600,
	  navigation: true,
	  navigationText: ['<i class="ion-ios-arrow-left"></i>','<i class="ion-ios-arrow-right"></i>']
	});


	// Testimonial Carousel
	$('.testimonial_carousel').owlCarousel({
		autoPlay: 10000,
		singleItem: true,
	  slideSpeed : 600,
	  navigation: true,
	  navigationText: ['<i class="ion-ios-arrow-left"></i>','<i class="ion-ios-arrow-right"></i>']
	});



	// Go to Top
	if($('#goToTop').length) {
		var scrollTrigger = 400,
		goToTop = function() {
			var scrollTop = $(window).scrollTop();
			if (scrollTop > scrollTrigger) {
				$('#goToTop').addClass('show');
			} else {
				$('#goToTop').removeClass('show');
			}
		};

		goToTop();

		$(window).on('scroll', function() {
			goToTop();
		});

		$('#goToTop').on('click', function(e) {
			e.preventDefault();
			$('html,body').animate({
				scrollTop: 0
			}, 700);
		});
	}


	// SHOW SECTION HOMEPAGE

	// Show/Hide (Hot Deals)
	$('.hot_deals_btn').on('click', function() {
		$('.hot_deals_btn').addClass('active');
		$('.xprs_menu_btn').removeClass('active');
		$('.catering_menu_btn').removeClass('active');
		$('.hot_deals_sec').addClass('show_sec');
		$('.xprs_menu_sec').removeClass('show_sec');
		$('.catering_menu_sec').removeClass('show_sec');
	});

	// Show/Hide (Xprs Menu)
	$('.xprs_menu_btn').on('click', function() {
		$('.xprs_menu_btn').addClass('active');
		$('.hot_deals_btn').removeClass('active');
		$('.catering_menu_btn').removeClass('active');
		$('.xprs_menu_sec').addClass('show_sec');
		$('.hot_deals_sec').removeClass('show_sec');
		$('.catering_menu_sec').removeClass('show_sec');
	});

	// Show/Hide (Catering Menu)
	$('.catering_menu_btn').on('click', function() {
		$('.catering_menu_btn').addClass('active');
		$('.xprs_menu_btn').removeClass('active');
		$('.hot_deals_btn').removeClass('active');
		$('.catering_menu_sec').addClass('show_sec');
		$('.xprs_menu_sec').removeClass('show_sec');
		$('.hot_deals_sec').removeClass('show_sec');
	});



	// Bueno Select2 Plugin (Initiation)
	$('.bueno_select2').select2({
		minimumResultsForSearch: Infinity,
	});

	// Price Range for Filters
	$('#priceRange').slider({});

	// Cart Scrolled to 0 (initially)
	var cart_scrolled = 0;

	// Cart Item Scroller (Up)
	$('#scrollToPrevItem').on("click", function(e) {
		e.stopPropagation();
		e.preventDefault();
		cart_scrolled = cart_scrolled - 90.156;

		$('.cart_item_ul').animate({
			scrollTop: cart_scrolled
		});

	});

	// Cart Item Scroller (Down)
	$('#scrollToNextItem').on("click", function(e) {
		e.stopPropagation();
		e.preventDefault();
		cart_scrolled = cart_scrolled + 90.156;

		$('.cart_item_ul').animate({
			scrollTop: cart_scrolled
		});
		
	});


	// Accordian (Show More/Less) with arrows
	$('.accordian_link').on('click', function() {
		if($(this).hasClass('collapsed')) {
			$(this).text('Know Less');
		} else {
			$(this).text('Know More');
		}
	});

});













