    var cartItemsCount = 0;
    var cartItems =  null;
    var cartTotal = 0;

    var renderHeaderCartNavLink = function(){
        $(document).find('.cart_link_count').text(cartItemsCount);
    };

    function disableCartNavArrows() {
        $(document).find('#scrollToPrevItem, #scrollToNextItem').addClass('disabled');
    }

    function enableCartNavArrows() {
        $(document).find('#scrollToPrevItem, #scrollToNextItem').removeClass('disabled');
    }

    var renderHeaderCartMenuBox = function(){
        var compiled = _.template($("#header-cart").html());
        html = compiled({
            cart_items : cartItems
        });

        calculateCartTotal();

        $('#header-cart-box').html(html);
        $('.cart_total_amount').text(cartTotal.toFixed(2));

        if(cartItems.length == 1){
            disableCartNavArrows();
        }else{
            enableCartNavArrows();
        }
    };

    var notify = function(text, type){
        var n = noty({
            text: text ,
            layout : 'topRight',
            type : type,
            animation: {
                open: 'animated fadeInDown', // Animate.css class names
                close: 'animated fadeOutDown', // Animate.css class names
                easing: 'swing', // unavailable - no need
                speed: 300 // unavailable - no need
            },
        });

        n.setTimeout(3000);
    };


    var addItemToCart = function(e){
        e.preventDefault();

        var button = $(this);
        var item = $(this).parents('.listing_item');
        var formData = {
            '_token' : button.data('token'),
            'item_id' : button.data('id'),
            'quantity' : item.find('.item_quantity_select').val()
        };

        $.ajax({
            url : button.data("url"),
            type : 'POST',
            data : formData
        }).done(function(response){
            if(response.success){

                fbq('track', 'AddToCart');
                cartItems = response.data.items;
                cartItemsCount = cartItems.length;

                renderHeaderCartNavLink();
                renderHeaderCartMenuBox();

                notify('Item Added', 'success');
                item.find('.item_quantity_select').val(1);
            }else{
                notify(response.errors[0].message, 'error');
                item.find('.item_quantity_select').val(1);
            }
        });
    };

    var addSavedItemToCart = function(e){
        e.preventDefault();

        var button = $(this);
        var item = $(this).parents('.saved_item_row');
        var formData = {
            '_token' : button.data('token'),
            'item_id' : button.data('id'),
            'quantity' : item.find('.item_quantity_select').val()
        };

        $.ajax({
            url : button.data("url"),
            type : 'POST',
            data : formData
        }).done(function(response){
            if(response.success){

                cartItems = response.data.items;
                cartItemsCount = cartItems.length;

                renderHeaderCartNavLink();
                renderHeaderCartMenuBox();

                notify('Item Added', 'success');
            }else{
                notify(response.errors[0].message, 'error');
            }
        });
    };

    var addHotDealToCart = function(e){
        e.preventDefault();

        var button = $(this);
        var item = $(this).parents('.hot_deal_item');
        var formData = {
            '_token' : button.data('token'),
            'item_id' : button.data('id'),
            'quantity' : item.find('.item_quantity_select').val()
        };

        $.ajax({
            url : button.data("url"),
            type : 'POST',
            data : formData
        }).done(function(response){
            if(response.success){
                cartItems = response.data.items;
                cartItemsCount = cartItems.length;

                renderHeaderCartNavLink();
                renderHeaderCartMenuBox();

                notify('Item Added', 'success');
            }else{
                notify(response.errors[0].message, 'error');
            }
        });
    };


    var renderCartPage = function(form){
        cart_item_id = form.find("input[name='cart_id']").val();
        cart_page_form = $('.cart_page_form');

        if(cart_page_form.length){
            cart_id_input = cart_page_form.find('.cart_item_row input[name="cart_id"][value=' + cart_item_id +']');
            row = cart_id_input.parents('.cart_item_row');

            row.fadeOut(400, function() {
                row.remove();
                updateTotalAmount();
                checkIfCartIsEmpty();
                checkIfCartHasErrors();


            });
        }
    };

    var removeCartItem = function(e){
        e.preventDefault();
        e.stopPropagation();


        var form = $(this).parents('form');

        $.ajax({
            url : form.attr('action'),
            type : 'POST',
            data : form.serialize()
        }).done(function(data){
            cartItems = data.data.items;
            cartItemsCount = cartItems.length;

            renderHeaderCartNavLink();
            renderHeaderCartMenuBox();
            notify('Item Removed !', 'success');
            disablePaymentButton(false);
            removeCoupon(e);
            renderCartPage(form);

        });
    };

    var calculateCartTotal = function(){
        cartTotal = 0;
        cartItems.map(function(cart_item){
            cartTotal += cart_item.item.itemable.discount_price * cart_item.quantity
        });
    };

    var addSavedItemsToCart = function(e){
        e.preventDefault();

        if($(this).hasClass('disabled')){
            return false;
        }

        form = $(this).parents('form');

        $.ajax({
            url : form.attr('action'),
            type : 'POST',
            data : form.serialize()
        }).done(function(response){
            if(response.success){

                cartItems = response.data.items;
                cartItemsCount = cartItems.length;

                renderHeaderCartNavLink();
                renderHeaderCartMenuBox();

                notify('All Saved Items Added to Cart', 'success');
            }else{
                notify(response.errors[0].message, 'error');
            }
        });
    };

    var deleteSavedItem = function(e){
        e.preventDefault();
        var button = $(this);
        var row = button.parents('.saved_item_row');
        var formData = {
            '_token' : button.data('token'),
            'item_id' : button.data('id'),
        };

        $.ajax({
            url : button.data("url"),
            type : 'DELETE',
            data : formData
        }).done(function(response){
            if(response.success){
                row.fadeOut(400, function() { 
                    row.remove();
                notify('Item Removed', 'success');

                item_count = $('.saved_item_row').length;
                currentCount = $('.saved_items_count');
                currentCount.text(parseInt(currentCount.text()) - 1);
                checkIfSavedHasErrors();

                if(item_count == 0){
                    html = ' <div class="col-xs-12 placeholder_message">';
                    html += '<h4 class="normal_header"><i class="ion-android-alert paddingright-xs"></i>Sorry, there are no items to show.</h4>';
                    html += '</div>';
                    $('.saved_item_container').empty().append(html);
                }
                 });
            }else{
                notify(response.errors[0].message, 'error');
            }
        });
    };

    var checkIfSavedHasErrors = function() {
        can_add = true;
        if($('.saved_item_container').length){
            saved_items = $('.saved_item_row');
            saved_items.each(function(index){
                if($(this).hasClass('disabled')){
                    can_add = false && can_add;
                }
            });

            if(can_add){
                $('.add_all_saved').removeClass('disabled');
            }
        }
    };


    var init = function(){
        cart = $('.header_cart_item_ul');
        cartItemsCount = cart.find('.cart_item_li').length;
    };

    // initiate everything. yes everything.
    init();

    // binding events
    $(document).on('click', '.add_to_cart', addItemToCart);
    $(document).on('click', '.add_saved_to_cart', addSavedItemToCart);
    $(document).on('click', '.remove_from_cart', removeCartItem);
    $(document).on('click', '.add_deal_to_cart', addHotDealToCart);
    $(document).on('click', '.add_all_saved', addSavedItemsToCart);
    $(document).on('click', '.delete_from_saved', deleteSavedItem);
