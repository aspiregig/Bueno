
    var total_checkout_amount = 0;
    var min_order_amount = 0;
    var formValid = true;

    var updateTotalAmount = function(){

        formValid = true;

        var total = 0;
        // 1. calculate items price
        cart_items = $('.cart_item_row').not('.coupon_item_row');
        cart_items.each(function(index){
            total += parseFloat($(this).find('.cart_item_rate').val()) * parseFloat($(this).find('.cart_item_quantity').val());
        });
        total_checkout_amount = total;
        checkIfCartHasMinimumOrderAmount(total);
        checkIfCartHasMaximumOrderAmount(total);
        checkIfCartHasAnyDisabledItem();

        // 2. subtract coupon amount
        discount = $(document).find('.discount_amount_input');
        if(discount.length){
            if(total - parseFloat(discount.val()) >= 0)
            {
                total -= parseFloat(discount.val());
            }else{
                total = 0;
            }
        }

        credit_amount_elem = $('.bueno_points_checkbox');
        points = Math.ceil(parseInt(credit_amount_elem.data('points')));
        if(points > total/2)
            points = Math.ceil(total/2);
        $(document).find('.bueno_usable_points_view').text(points);

        // 3. subtract bueno points
        bueno_points = $('.bueno_points_checkbox:checked');
        if(bueno_points.length){
            discount = total > points ? points : total;
            total -= discount;
            $(document).find('.bueno_points_amount').remove();
            // add bueno points in final sum
            html = '<div class="full_width bueno_points_amount">';
            html += '<p class="total_heading">Loyalty Points</p>';
            html += '<p class="total_content">- ₹ '+ discount.toFixed(2) + '</p>';
            html += '</div>';

            $(html).insertBefore('.vat_amount');
        }else{
            $(document).find('.bueno_points_amount').remove();
        }

        // 3. apply vat and tax
        vat = $('.vat_input').val() ? calculteVat(total, $('.vat_input').val()) : 0;
        service_tax = $('.service_tax_input').val() ? calculteServiceTax(total, $('.service_tax_input').val()) : 0;
        packaging_charge = $('.packaging_charge_input').val()  ? $('.packaging_charge_input').val() : 0;
        service_charge = $('.service_charge_input').val() ? $('.service_charge_input').val() : 0;
        delivery_charge =  $('.delivery_charge_input').val() ? $('.delivery_charge_input').val() : 0;

        total += parseFloat(vat) + parseFloat(service_tax) + parseFloat(packaging_charge) + parseFloat(service_charge) + parseFloat(delivery_charge);

        $('.vat_amount_text span').text(vat.toFixed(2));
        $('.service_tax_text span').text(service_tax.toFixed(2));

        // 4. calculate donation price
        donation = $('.donation .donation_checkbox:checked');
        if(donation.length){
            total += donation.data('amount');
        }

        $('.checkout-total-amount').text(total.toFixed(2));

        // checking for errors
        if(formValid){
            checkIfUserHasAddresses();
        }

        checkIfTotalPrizeIsZero(total);

        disablePaymentButton(!formValid);
    };

    var checkIfTotalPrizeIsZero = function(total){
        total = parseFloat(total);
        if(total == 0){
            $('.cod_payment').prop('checked', true)
        }
    }

    var checkIfCartHasAnyDisabledItem = function(){
        disabled_items = $('.disabled_item');
        $(document).find('.disabled_item_error').remove();
        if(disabled_items.length){
            html = '<div class="checkout-error disabled_item_error">';
            html += '<p>Please remove disabled items from cart in order to proceed.</p>';
            html += '</div>';

            formValid = formValid && false;

            $('.checkout-error-container').append(html);
        }else{
            formValid = formValid && true;
        }
    };

    var checkIfCartHasMaximumOrderAmount = function(total_amount){
        $('.maximum_order_amount_error').remove();
        // check for minimum amount
        if(total_amount > 2500 ){
            html = '<div class="checkout-error minimum_order_amount_error">';
            html += '<p>You can order maximum of Rs. 2500</p>';
            html += '</div>';

            $('.checkout-error-container').append(html);
            formValid = formValid && false;
        }else{
            formValid = formValid && true;
        }
    };

    var checkIfCartHasMinimumOrderAmount = function(total_amount){
        $('.minimum_order_amount_error').remove();
        // check for minimum amount
        if(min_order_amount > total_amount ){
            html = '<div class="checkout-error minimum_order_amount_error">';
            html += '<p>You have to order minimum of Rs.' + min_order_amount + '</p>';
            html += '</div>';

            $('.checkout-error-container').append(html);
            formValid = formValid && false;
        }else{
            formValid = formValid && true;
        }
    };

    // checks if items are in stocks and also checks for their status
    var checkIfItemsAreInStock = function(){
        $('.cart_item_error').empty();
        items = $('.cart_item_row');
        items.each(function(){
            item = $(this);
            stock = parseInt(item.find('.cart_item_stock').val());
            quantity = parseInt(item.find('.cart_item_quantity').val());

            if(quantity > stock){
                
                html = '<div class="bueno_form_group has-error">';
                html += '<span class="help-block">You can order '+ stock + ' max of this item right now' +'.</span>';
                html += '</div>';

                if(parseInt(stock) == 0){
                     html = '<div class="bueno_form_group has-error">';
                    html += '<span class="help-block">This item is out of stock</span>';
                    html += '</div>';
                    
                    item.addClass('out_of_stock');
                }
                item.find('.cart_item_error').empty().html(html);

                item.find('.cart_item_quantity').addClass('disabled');
                formValid = formValid && false;

            }else{
                formValid = formValid && true;
            }

            if(item.hasClass('disabled_item')){
                html = '<div class="bueno_form_group has-error">';
                html += '<span class="help-block">This item is not for sale.</span>';
                html += '</div>';

                item.find('.cart_item_error').empty().html(html);
                item.find('.cart_item_quantity').addClass('disabled');

                formValid = formValid && false;
            }else{
                formValid = formValid && true;
            }
        });
    };

    var disablePaymentButton = function(flag){
        var payment_button = $('#payment_button');
        if(flag){
            payment_button.addClass('disabled');
        }else{
            payment_button.removeClass('disabled');
        }
    };

    var checkIfUserHasAddresses = function(){
        $('.user_address_checkout_error').remove();

        count = $(document).find('.user_address_row').not('.disabled').length;
        if(count){
            formValid = formValid && true;
        }else{
            formValid = formValid && false;
        }

        checkedAddress = $(document).find('.user_address_row input[type=radio]:checked').length;

        if(checkedAddress){
            formValid = formValid && true;
        }else{
            formValid = formValid && false;

            html = '<div class="checkout-error user_address_checkout_error">';
            html += '<p>Please select an address to proceed.</p>';
            html += '</div>';

            $('.checkout-error-container').append(html);
        }
    };

    var addressChecked = function(){
        checkIfCartHasErrors();
    };

    var checkIfCartHasErrors = function(){
        formValid = true;
        checkIfItemsAreInStock();
        checkIfUserHasAddresses();
        checkIfCartHasMinimumOrderAmount(total_checkout_amount);
        checkIfCartHasMaximumOrderAmount(total_checkout_amount);
        checkIfCartHasAnyDisabledItem();
        disablePaymentButton(!formValid);
    };


    var calculteServiceTax = function(total, service_tax){
        var service_tax_amount = total * service_tax * .01;
        return service_tax_amount;
    };
    var calculteVat = function(total, vat){
        var vat_amount = total * vat * .01;
        return vat_amount;
    };

    var removeCheckboxAmount = function(except){
        var donation_checkboxes = $('.donation_checkbox').not(except);
        donation_checkboxes.each(function(index){
            input = $(this);
            isSelected = input.is(':checked');

            input.removeAttr('checked');
        });
    };

    var removeCheckboxInstruction = function(except){
        var instruction_checkboxes= $('.instruction_checkbox').not(except);
        instruction_checkboxes.each(function(index){
            input = $(this);
            isSelected = input.is(':checked');

            input.removeAttr('checked');
        });
    };


    var addRemoveInstruction = function(e){
        // removing all previous donation
        $(document).find('.instruction_textarea').removeAttr('disabled');

        instructionInput = $(this);
        removeCheckboxInstruction(instructionInput);

        selected =  instructionInput.is(':checked');

        if(selected){
            $(document).find('.instruction_textarea').attr('disabled', 'disabled');
        }

    };

    var addRemoveDonation = function(e){

        // removing all previous donation
        $(document).find('.donation_amount').remove();

        donationInput = $(this);

        selected =  donationInput.is(':checked');
        removeCheckboxAmount(donationInput);

        html = '<div class="full_width donation_amount">';
        html += '<p class="total_heading">' + donationInput.data('name') + '</p>';

        html += '<p class="total_content">₹ ' +  donationInput.data('amount') +' </p>';
        html += '</div>';

        if(selected){
            $(html).insertBefore('.vat_amount');
        }

        updateTotalAmount();
    };

    var useBuenoPoints = function(e){

        updateTotalAmount();

    };

   var getTotalItemQuantitySum =  function () {
        cart_items = $('.cart_item_row ');
        total_items_quantity = 0;
        cart_items.each(function(index){
            cart_item = $(this);
            total_items_quantity  += parseInt(cart_item.find('.cart_item_quantity').val());
        });

        return total_items_quantity;
    };

    var changeItemQuantity = function(e){
        
        if(e.keyCode == 13){
            e.stopPropagation();
            e.preventDefault();
        }

        disablePaymentButton(false);
        input = $(this);

        var html = '';
        row = $(this).parents('tr');
        var value = row.find('.cart_item_rate').val();
        var stock = parseInt(row.find('.cart_item_stock').val());
        var quantity = parseInt($(this).val());

        // remove errors
        row.find('.cart_item_error').empty();

        if(quantity > stock){
            html = '<div class="bueno_form_group has-error">';
            html += '<span class="help-block">You cannot order more than ' + stock +' right now</span>';
            html += '</div>';

            quantity = stock;

            input.val(stock);
        }else if(quantity > 10){
            quantity = 10;
            $(this).val(10);

            html = '<div class="bueno_form_group has-error">';
            html += '<span class="help-block">You cannot order more than 10</span>';
            html += '</div>';
        } else if(quantity < 1 || isNaN(quantity)){
            quantity = 1;
            $(this).val(1);
        }

        if(getTotalItemQuantitySum() > 10){
            html = '<div class="bueno_form_group has-error">';
            html += '<span class="help-block">You cannot order more than 10 items</span>';
            html += '</div>';

            input.val(parseInt(getItemSumValue(row)));
            quantity = parseInt(getItemSumValue(row));
        }

        saveQuantity(input);

        row.find('.cart_item_error').empty().html(html);

        row.find('.cart_item_price').text((value * quantity).toFixed(2));
        removeCoupon(e);
        updateTotalAmount();
    };

    var getItemSumValue = function(itemToIgnore)
    {
        cart_items = $('.cart_item_row ').not(itemToIgnore);
        total_items_quantity = 0;
        cart_items.each(function(index){
            cart_item = $(this);
            total_items_quantity  += parseInt(cart_item.find('.cart_item_quantity').val());
        });

        return (10 - total_items_quantity);
    }

    var showCouponError = function(text){
        $('.coupon-error').text(text);
    };

    var hideCouponError = function(){
        $('.coupon-error').text('');
    };

    var removeCoupon = function(e){
        hideCouponError();

        e.preventDefault();
        coupon = $('.apply_coupon_text');
        removeCouponButton = $(document).find('.remove_coupon');
        couponInput = $(document).find('.discount_amount');
        coupon_items = $(document).find('.coupon_item_row');
        counpon_points = $(document).find('.coupon-points');
        coupon_text = $(document).find('.coupon_text');

        coupon_items.remove();
        coupon.val('').removeClass('disabled');
        $('.apply_coupon_box').removeClass('disabled');
        removeCouponButton.remove();
        counpon_points.empty();
        couponInput.remove();
        coupon_text.remove();

        disablePaymentMethods(false);
        updateTotalAmount();
    };

    var disableCoupon = function(){
        hideCouponError();
        
        coupon = $('.apply_coupon_text');
        removeCouponButton = '<a href="#" class="remove_coupon">Remove Coupon</a>';

        // couponText = '<em class="coupon_text">If you change any item quantity, the coupon will be removed !</em>';
        
        $('.apply_coupon_box').addClass('disabled');
        $(document).find('.remove_coupon').remove();
        $(removeCouponButton).insertAfter('.coupon-error');
        // $(couponText).insertAfter('.remove_coupon');
    };

    var disablePaymentMethods = function(boolean) {
        if(boolean){
            $('#payment_method_accordion').addClass('disabled');
        }else{
            $('#payment_method_accordion').removeClass('disabled');
        }
    };
    var applyCoupon = function(e){
        e.preventDefault();
        hideCouponError();

        coupon = $('.apply_coupon_text');
        if(coupon.val().length == 0){
            showCouponError('Please enter coupon');
            return false;
        }else if(coupon.val().length > 40){
            showCouponError('Please enter valid coupon');
            return false;
        }

        form = $(this).parents('form');
        

        $.ajax({
            url : coupon.data('url'),
            type : 'GET',
            data : form.serialize()
        }).done(function(response){
            if(response.success){

                $(document).find('.discount_amount').remove();

                if(response.data.discount){
                    html = '  <div class="full_width discount_amount">';
                    html += '<p class="total_heading">Discount</p>';
                    html += '<input type="hidden" class="discount_amount_input" value="'+ response.data.discount +'">';
                    html += '<p class="total_content">- ₹ '+ response.data.discount +'</p>';
                    html += '</div>';

                    $(html).insertBefore('.vat_amount');
                }

                if(response.data.items){
                    items = response.data.items;
                    for(var i in items){
                        item = '<tr class="cart_item_row coupon_item_row">';
                        item += '<td class="product_detail">';
                        item += '<div class="sec_table">';
                        item += '<div class="image sec_table_cell">';
                        item += '<div class="cart_page_item_image" style="background-image: url('+ items[i]['image_url'] + ')"></div>';
                        item += '</div> <!-- image ends -->';
                        item += '<div class="details sec_table_cell">';
                        item += '<div class="details_holder">';
                        item += '<h4 class="title">' + items[i]['name']+'</h4>';
                        item += '<div class="action_container col-xs-12">';
                        item += '</div> <!-- action_container ends -->';
                        item += '</div> <!-- details_holder ends -->';
                        item += '</div> <!-- details ends -->';
                        item += '</div> <!-- sec_table ends -->';
                        item += '</td> <!-- product_detail ends -->';
                        item += '<td class="product_quantity"><p>'+ items[i]['quantity'] +'</p> <br />';
                        item += '</td> <!-- product_quantity ends -->';
                        item += '<td class="product_rate">';
                        item += '<p>-</p>';
                        item += '</td> <!-- product_rate ends -->';
                        item += '<td class="product_price">';
                        item += '<p>-</p>';
                        item += '</td> <!-- product_price ends --></tr>';

                        $('.cart_item_container').append(item);
                    }
                }

                if(response.data.cashback){
                    points = '<em>You will get ' + parseInt(response.data.cashback) + ' Bueno Credits</em>'
                    $('.coupon-points').html(points);
                }


                disableCoupon(coupon);
                disablePaymentMethods(true);
                updateTotalAmount();
            }else{
                showCouponError(response.errors[0].message);
            }
        });

    };

    var init = function(){
        min_order_amount = parseFloat($('.min_order_amount').val());
        updateTotalAmount();
        checkIfCartHasErrors();
    };

    var saveItem = function(e){
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
                $('.saved_items_count').text(data.data.length);
                notify('Item Saved', 'success');
            }else{
                notify( data.errors[0].message, 'error');
            }
        });
    };

    var checkIfCartIsEmpty = function(){
        item_count = $('.cart_item_row').length;
        form = $('.cart_page_form');
        if(!item_count){
            form.empty();

            html = '<div class="col-xs-12 placeholder_message">';
            html += '<h4 class="normal_header"><i class="ion-android-alert paddingright-xs"></i>Your cart is empty ! </h4>';
            html += '</div>';

            form.append(html);
        }
    };

    var removeItemFromPage = function(e){
        e.preventDefault();

        var button = $(this);
        var row = button.parents('tr').first();
        var formData = {
            '_token' : button.data('token'),
            'cart_id': button.data('id')
        };

        disablePaymentButton(false);

        $.ajax({
            url : button.data('url'),
            type : 'DELETE',
            data : formData
        }).done(function(data){
            cartItems = data.data.items;
            cartItemsCount = cartItems.length;

            renderHeaderCartNavLink();
            renderHeaderCartMenuBox();

            row.fadeOut(400, function() { 
                row.remove();
                updateTotalAmount();
                removeCoupon(e);
                checkIfCartIsEmpty();
                checkIfCartHasErrors();

                notify('Item Removed', 'error');
            });
        });
    };

    var addNewAddress = function(e){
        form = $(this);
        e.preventDefault();
        $.ajax({
            url : form.attr('action'),
            data : form.serialize(),
            type : 'POST'
        }).done(function(response){
            $(".modal-errors-list").empty();
            if(response.success){
                html = '<div class="radio col-xs-12 col-md-4 user_address_row">';
                html += '<label class="left full_width">';
                html += '<span class="txt"><strong class="text-uppercase">' +  response.data.address_name + ' </strong>';
                html += '<p class="normal_para">' + response.data.address + '</p></span>';
                html += '<input type="radio" name="address_id" value="' + response.data.id + '" class="inputradio" id="radio1" checked>';
                html += '<span class="check_style left"></span>';
                html += '</label>';
                html += '</div>';

                $('.delivery_addresses').append(html);

                form.find('input[type=text]').val('');
                $('#addAddressModal').modal('hide');
                $('.address_placeholder_message').remove();

                checkIfCartHasErrors();
            }else{
                html = '<ul class="validation-errors">';
                errors = response.errors[0].message;
                for(var i in errors){
                    html += '<li>' + errors[i] + '</li>';
                }

                html += '</ul>';

                $(".modal-errors-list").append(html);
            }
        });
    };

    var savingTimer;
    var saveQuantity = function(input){
        clearTimeout(savingTimer);
        var formData = {
            quantity : input.val(),
            cart_id : input.data('cart'),
            _token : input.data('token')
        };
        if(quantity > 0){
            savingTimer = setTimeout(function(){
                $.ajax({
                    url : input.data('url'),
                    type : 'POST',
                    data : formData
                }).done(function(response){
                    if(response.success){
                            cartItems = response.data.items;
                            cartItemsCount = cartItems.length;

                            renderHeaderCartNavLink();
                            renderHeaderCartMenuBox();
                        }
                    });
            }, 400);    
        }
    };

    var changeCouponText = function(e){
        e.stopPropagation();
        keyCode = e.keyCode;
        
        if(keyCode == 13){
            e.preventDefault();
            $('.apply_coupon_button').trigger('click');
        }
    };

    var submitCheckoutForm = function(e) {
        if(formValid){
            fbq('track', 'InitiateCheckout');
        }
    };

    //binding events
    $(document).ready(init);
    $(document).on('click', '#payment_button', submitCheckoutForm);
    $(document).on('change', '.cart_item_quantity', changeItemQuantity);
    $(document).on('keypress keyup', '.cart_item_quantity', function(e){ if(e.keyCode == 13) return false; });
    $(document).on('change', '.donation_checkbox', addRemoveDonation);
    $(document).on('change', '.instruction_checkbox', addRemoveInstruction);
    $(document).on('change', '.bueno_points_checkbox', useBuenoPoints);
    $(document).on('click', '.apply_coupon_button', applyCoupon);
    $(document).on('keyup', '.apply_coupon_text', changeCouponText);
    $(document).on('keydown', '.apply_coupon_text', function(e){ if(e.keyCode == 13){e.preventDefault();} });
    $(document).on('click', '.remove_coupon', removeCoupon);
    $(document).on('click', '.save_for_later', saveItem);
    $(document).on('click', '.remove_from_cart_page', removeItemFromPage);
    $(document).on('submit', '.delivery_address_modal', addNewAddress);
    $(document).on('change', '.user_address_row input[type=radio]', addressChecked);
