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
        autoPlay: 5000,
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
    // $('.hot_deals_btn').on('click', function() {
    //     $('.hot_deals_btn').addClass('active');
    //     $('.xprs_menu_btn').removeClass('active');
    //     $('.catering_menu_btn').removeClass('active');
    //     $('.hot_deals_sec').addClass('show_sec');
    //     $('.xprs_menu_sec').removeClass('show_sec');
    //     $('.catering_menu_sec').removeClass('show_sec');
    // });

    // Show/Hide (Xprs Menu)
    // $('.xprs_menu_btn').on('click', function() {
    //     $('.xprs_menu_btn').addClass('active');
    //     $('.hot_deals_btn').removeClass('active');
    //     $('.catering_menu_btn').removeClass('active');
    //     $('.xprs_menu_sec').addClass('show_sec');
    //     $('.hot_deals_sec').removeClass('show_sec');
    //     $('.catering_menu_sec').removeClass('show_sec');
    // });

    // Show/Hide (Catering Menu)
    // $('.catering_menu_btn').on('click', function() {
    //     $('.catering_menu_btn').addClass('active');
    //     $('.xprs_menu_btn').removeClass('active');
    //     $('.hot_deals_btn').removeClass('active');
    //     $('.catering_menu_sec').addClass('show_sec');
    //     $('.xprs_menu_sec').removeClass('show_sec');
    //     $('.hot_deals_sec').removeClass('show_sec');
    // });



    // Bueno Select2 Plugin (Initiation)
    $('.bueno_select2').select2({
        minimumResultsForSearch: Infinity
    });

    // Price Range for Filters
    $('#priceRange').slider({});

    // Cart Scrolled to 0 (initially)
    var cart_scrolled = 0;

    // Cart Item Scroller (Up)
    $(document).on("click",'#scrollToPrevItem', function(e) {
        e.stopPropagation();
        e.preventDefault();
        cart_scrolled = cart_scrolled - 90.156;

        $('.cart_item_ul').animate({
            scrollTop: cart_scrolled
        });

    });

    // Cart Item Scroller (Down)
    $(document).on("click", '#scrollToNextItem', function(e) {
        e.stopPropagation();
        e.preventDefault();
        cart_scrolled = cart_scrolled + 90.156;

        $('.cart_item_ul').animate({
            scrollTop: cart_scrolled
        });

    });


    // Accordian (Show More/Less) with arrows
    $('.accordian_link.normal').on('click', function() {
        if($(this).hasClass('collapsed')) {
            $(this).text('Know Less');
        } else {
            $(this).text('Know More');
        }
    });


    // Edit <textarea> toggle (Bueno Loyalty Page)
    $('#messageToggle').on('click', function() {
        if ($('#message').hasClass('disabled')) {
            $('#messageToggle').text('save');
            $('#message').removeClass('disabled');
        } else {
            $('#messageToggle').text('edit');
            $('#message').addClass('disabled');
        }
    });


    // Cart Box Shadow Conditional
    var cart_table_width = $('.cart_table_responsive .cart_table').innerWidth();
    var window_width = $(window).innerWidth();
    if (cart_table_width > window_width) {
        $('.cart_table_responsive').addClass('table_shadow_right');
        $('.cart_table_responsive').scroll(function() {
            var tableScroll = $('.cart_table_responsive').scrollLeft();
            var maxTableScroll = $('.cart_table_responsive .cart_table').width() - $('.cart_table_responsive').width();
            if ((tableScroll >= 1) && (tableScroll < maxTableScroll)) {
                $('.cart_table_responsive').removeClass('table_shadow_left');
                $('.cart_table_responsive').removeClass('table_shadow_right');
                $('.cart_table_responsive').addClass('table_shadow_rl');
            } else if (tableScroll == maxTableScroll) {
                $('.cart_table_responsive').removeClass('table_shadow_rl');
                $('.cart_table_responsive').addClass('table_shadow_left');
            } else if (tableScroll == 0) {
                $('.cart_table_responsive').removeClass('table_shadow_rl');
                $('.cart_table_responsive').addClass('table_shadow_right');
            } else {
                $('.cart_table_responsive').removeClass('table_shadow_rl');
                $('.cart_table_responsive').addClass('table_shadow_right');
            }
        });
    } else {
        $('.cart_table_responsive').scroll(function() {
            var tableScroll = $('.cart_table_responsive').scrollLeft();
            var maxTableScroll = $('.cart_table_responsive .cart_table').width() - $('.cart_table_responsive').width();
            if ((tableScroll >= 1) && (tableScroll < maxTableScroll)) {
                $('.cart_table_responsive').removeClass('table_shadow_left');
                $('.cart_table_responsive').removeClass('table_shadow_right');
                $('.cart_table_responsive').addClass('table_shadow_rl');
            } else if (tableScroll == maxTableScroll) {
                $('.cart_table_responsive').removeClass('table_shadow_rl');
                $('.cart_table_responsive').addClass('table_shadow_left');
            } else if (tableScroll == 0) {
                console.log('== 0');
                $('.cart_table_responsive').removeClass('table_shadow_rl');
                $('.cart_table_responsive').addClass('table_shadow_right');
            } else {
                console.log('else');
                $('.cart_table_responsive').removeClass('table_shadow_rl');
                $('.cart_table_responsive').addClass('table_shadow_right');
            }
        });
    }

});

var items = [];
locality_select = $('.locality_select2');

locality_select.select2({
    ajax: {
        url: "/areas",
        delay: 250,
        data: function (params) {
            return {
                q: params.term, // search term
                page: params.page
            };
        },
        processResults: function (data, page) {
            items = [];
            for(i in data)
            {
                items.push({ "text" : data[i]['name'], "id" :data[i]['id']  });
            }

            return {
                results: items
            };
        },
        cache: true
    },
    data : items,
    minimumInputLength: 1,
    escapeMarkup: function (markup) { return markup; }
});

locality_select.on("select2:select", function (e) {
    e.preventDefault();
    var form = $(this).parents('form');
    form.submit();
});

//var locality_modal_select = $('.locality_select_modal select');
//
//locality_modal_select.on("select2:select", function (e) {
//    e.preventDefault();
//    var form = $(this).parents('form');
//    $.ajax({
//        url : form.attr('action'),
//        type : 'POST',
//        data : form.serialize()
//    }).done(function(data){
//        location.reload();
//    });
//});

// add to favorite
$(document).on('click', '.add_to_favourite', function(e){
    e.preventDefault();

    var button = $(this);
    var formData = {
        '_token': button.data('token'),
        'item_id' : button.data('id')
    };

    $.ajax({
        url : button.data('url'),
        type : 'POST',
        data : formData
    }).done(function(data){
        if(data.success){
            fbq('track', 'AddToCart');
            notify('Item added to favorites', 'success');
            currentCount = $('.saved_items_count');
            currentCount.text(parseInt(currentCount.text()) + 1);
            button.addClass('favourite').removeClass('add_to_favourite');
            $('.add_to_favorite_text').text('Saved');
        }else{
            notify(data.errors[0].message, 'error');
        }
    });
});

$(document).on('click', '.favourite', function(e){
    e.preventDefault();

    var button = $(this);
    var formData = {
        '_token': button.data('token'),
        'item_id' : button.data('id')
    };

    $.ajax({
        url : button.data('url'),
        type : 'DELETE',
        data : formData
    }).done(function(data){
        if(data.success){
           notify('Item removed from favorites', 'success');
            currentCount = $('.saved_items_count');
            currentCount.text(parseInt(currentCount.text()) - 1);
            button.addClass('add_to_favourite').removeClass('favourite');
            $('.add_to_favorite_text').text('Save for Later');
        }else{
            notify(data.errors[0].message, 'error');
        }
    });
});

// saved items quantity change
$('.saved_item_quantity').change(function(e){
    input = $(this);
    row = input.parents('tr');
    if(input.val() > 10){
        input.val(10);
    }
    row.find('.item_quantity_select').val(input.val());
});

// add all saved items to cart
$('.add_all_saved').click(function(e){
    e.preventDefault();
});

// checking if area is set
var checkIfAreaIsSet = function(){
    var session_area_id = $('#session_area_id').val();
    var master_switch = parseInt($('#master_switch').val());
    var is_open = parseInt($('#is_open').val());
    if(!session_area_id.length){
        $('#localityModal').modal('show');
    }else if(!master_switch || !is_open){
        $('#master_switch_modal').modal('show');
    }
};

$(document).ready(checkIfAreaIsSet);


// homepage hot deals select change

var renderHomepageHotDeals = function(data){
    var compiled = _.template($("#hot-deals-homepage-listing").html());
    html = compiled({
        items : data
    });

    $('#homepage_hot_deals_container').empty().html(html);
    $('.meal_carousel').data('owlCarousel').destroy();
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

};


 var renderHotDealsPage = function(data){
     var compiled = _.template($("#hot-deals-listing-template").html());
     html = compiled({
         items : data
     });

     $('#hot_deals_page_container').empty().html(html);
};


$('#date_of_birth').datetimepicker({
    timepicker:false,
    format:'Y-m-d',
    maxDate : 0
});


$('.xprs_menu_form input').on('keyup keypress', function(e) {
    var keyCode = e.keyCode || e.which;
    if (keyCode === 13) {
        e.preventDefault();
        return false;
    }
});

    $.fn.customerPopup = function (e, intWidth, intHeight, blnResize) {

        // Prevent default anchor event
        e.preventDefault();

        // Set values for window
        intWidth = intWidth || '500';
        intHeight = intHeight || '400';
        strResize = (blnResize ? 'yes' : 'no');

        // Set title and open popup with focus on it
        var strTitle = ((typeof this.attr('title') !== 'undefined') ? this.attr('title') : 'Social Share'),
            strParam = 'width=' + intWidth + ',height=' + intHeight + ',resizable=' + strResize,
            objWindow = window.open(this.attr('href'), strTitle, strParam).focus();
    };

    /* ================================================== */


    $(document).on('click', '.share-popover', function(e) {
            $(this).customerPopup(e);
    });




// delete address
var deleteUserAddress = function(e){
    e.preventDefault();
    button = $(this);
    var x;
    if (confirm("Are you sure you want to delete the address") == true) {
        window.location = button.attr('href');
    } 
};

$(document).on('click', '.delete_address', deleteUserAddress);

// copy to clipboard

var clipboard = new Clipboard('.clipboard');

clipboard.on('success', function(e) {
    notify('Coupon Code Copied !', 'success');
});



$(document).ready(function(e){
    fbq('track', 'ViewContent');
});










