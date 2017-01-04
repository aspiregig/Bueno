@extends('admin.master')

  @section('title')Bueno Kitchen @endsection

  @section('header')


  <!-- stylesheets -->
  @include('admin.partials.css')

  <!-- javascript -->
  @include('admin.partials.js')

    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

  @endsection

  @section('content')

  <div id="content">
  @include('admin.partials.errors')
  @include('admin.partials.flash')

            <div class="menubar" >
                <div class="sidebar-toggler visible-xs">
                    <i class="ion-navicon"></i>
                </div>

                <div class="page-title">
                    Quick Order 
                </div>
            </div>
            <div id="dashboard">
                      <div class="metrics clearfix">
                        <div class="metric">
                          <span class="field">Total Items</span>
                          <span class="data" id="total-item">0</span>
                        </div>
                        <div class="metric">
                          <span class="field">Order Amount</span>
                          <span class="data" id="total-amount">INR <strong>0.0</strong></span>
                        </div>
                        <div class="metric">
                          <span class="field">Total Tax</span>
                          <span class="data" id="total-tax">INR <strong>0.0</strong></span>
                        </div>
                        <div class="metric">
                          <span class="field">Total Payable Amount</span>
                          <span class="data" id="total-order-amount">INR <strong>0.0</strong></span>
                        </div>
                        <div class="metric">
                              <span class="field">Discount</span>
                              <span class="data" id="total-discount">INR <strong>0.0</strong></span>
                        </div>
                          <div class="metric">
                          <span class="field">Credits Used</span>
                          <span class="data" id="total-points-used"><strong>0.0</strong></span>
                          </div>
                          <div class="metric">
                              <span class="field">Donation Amount</span>
                              <span class="data" id="total-donation-amount"><strong>0.0</strong></span>
                          </div>
                    </div>
            <div class="content-wrapper">
                <form class="form-horizontal" id="quick-order-form" method="post" role="form">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">First name</label>
                        <div class="col-sm-10 col-md-8">
                          <input type="text" class="form-control user-first-name" name="first_name" required="" value="{{old('first_name')}}"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Last name</label>
                        <div class="col-sm-10 col-md-8">
                          <input type="text" class="form-control user-last-name" name="last_name" value="{{old('last_name')}}"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Email address</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="text" class="form-control user-email" name="email" value="{{old('email')}}"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Contact number</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="has-feedback">
                                <input type="text" class="form-control user-phone" name="phone" required="" value="{{old('phone')}}"/>
                            </div>
                        </div>
                        <button id="get-phone-data">Get Data</button>
                    </div>
                    <div class="address">
                        <div class="form-group">
                            <label class="col-sm-2 col-md-2 control-label">Select an Address</label>
                            <div class="col-sm-10 col-md-8">
                                <select  class="form-control user-address"  name="user-address-option">
                                    <option value="new">Add an Address</option>
                                </select>
                            </div>
                        </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Full Address</label>
                        <div class="col-sm-10 col-md-8">
                            <textarea class="add-new-address form-control" name="address" required="">{{old('address')}}</textarea>
                        </div>
                    </div>
                    <input type="hidden" class="form-control" id="user-address-area-id" name="area_id" />
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Location</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="">

                                <select name="area_id" class="user-area form-control" required="" readonly="">
                                <option>Select a Locality</option>
                                @foreach($kitchens as $kitchen)
                                @foreach($kitchen->areas as $area)
                                @if($area->status==1)
                                      <option value="{{$area->id}}" data-minorderamount="{{$area->min_order_amount}}" data-servicetax="{{$kitchen->service_tax}}" data-vat="{{$kitchen->vat}}" data-deliverycharge="{{$kitchen->delivery_charge}}" data-servicecharge="{{$kitchen->service_charge}}" data-packagingcharge="{{$kitchen->packaging_charge}}">{{$area->name}}, {{$area->city->name}}</option>
                                @endif
                                @endforeach
                                @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                        </div>
                    @if($meals->count()!=0)
                    <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Meal(s) in Order</label>
              <div class="col-sm-10 col-md-8">
                  <div class="well">
                       <div class="control-group combo-meals">
                          <div class="combo-meals-options hidden">
                                @foreach($meals as $meal)
                                <option value="{{$meal->id}}" @if($meal->discount_price!=0)
                                data-amount="{{ $meal->discount_price }}"
                                @else
                                data-amount="{{ $meal->original_price }}" @endif>{{$meal->name}}</option>
                              @endforeach
                          </div>
                          <a href="#" class="btn btn-danger btn-xs" id="add-meal">add a meal</a>
                       </div>
                      </div>
                      <?php
                      $meal_count = 0;
                      ?>
                      @if(old('meals')!=null)
                      @foreach(old('meals') as $item)
                        <div class="input select add-new-combo-meal"><label>Meal</label>
                        <select name="meals[{{$meal_count}}][id]" class="meals_dp" >
                          @foreach($meals as $meal)
                                <option value="{{$meal->id}}"
                                @if($item['id']==$meal->id)
                                selected=""
                                @endif
                                @if($meal->discount_price!=0)
                                data-amount="{{ $meal->discount_price }}"
                                @else
                                data-amount="{{ $meal->original_price }}" @endif
                                >
                                {{$meal->name}}
                                </option>
                        @endforeach
                        </select>
                        <label for="OrderQuantity1">Quantity</label>
                        <input name="meals[{{$meal_count++}}][quantity]" class="qty_dp" value="{{$item['quantity']}}" type="number" min="0"  />
                        <a class="remove-meal">Remove</a>
                        <br>
                        </div>
                      @endforeach
                      @endif
                      <input type="hidden" class="meal-count" name="meal_count" @if(old('meal_count')!=null) value="{{old('meal_count')}}"@else value="0" @endif>
                      <input type="hidden" class="actual-meal-count" name="actual_meal_count" @if(old('actual_meal_count')!=null) value="{{old('actual_meal_count')}}"@else value="0" @endif>
                  </div>
            </div>
                    @endif
            @if($combos->count()!=0)
             <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Combo(s) in Order</label>
              <div class="col-sm-10 col-md-8">
                  <div class="well">
                       <div class="control-group combo-combos">
                          <div class="combo-combos-options hidden">
                              @foreach($combos as $combo)
                                <option value="{{$combo->id}}" @if($combo->discount_price!=0)
                                data-amount="{{ $combo->discount_price }}"
                                @else
                                data-amount="{{ $combo->original_price }}" @endif>{{$combo->name}}</option>
                              @endforeach
                          </div>
                          <a href="#" class="btn btn-danger btn-xs" id="add-combo">add a combo</a>
                       </div>
                      </div>
                      <?php
                      $combo_count = 0;
                      ?>
                      @if(old('combos')!=null)
                      @foreach(old('combos') as $item)
                        <div class="input select add-new-combo-combo"><label for="OrderMealId1">Combo</label>
                        <select name="combos[{{$combo_count}}][id]" class="combos_dp" >
                          @foreach($combos as $combo)
                                <option value="{{$combo->id}}"
                                @if($item['id']==$combo->id)
                                selected=""
                                @endif
                                @if($combo->discount_price!=0)
                                data-amount="{{ $combo->discount_price }}"
                                @else
                                data-amount="{{ $combo->original_price }}" @endif
                                >
                                {{$combo->name}}
                                </option>
                        @endforeach
                        </select>
                        <label for="OrderQuantity1">Quantity</label>
                        <input name="combos[{{$combo_count++}}][quantity]" class="qty_dp" value="{{$item['quantity']}}" type="number" min="1" max="10"/>
                        <a class="remove-combo">Remove</a>
                        <br>
                        </div>
                      @endforeach
                      @endif
                      <input type="hidden" class="combo-count" @if(old('combo_count')!=null) value="{{old('combo_count')}}"@else value="0" @endif>
                      <input type="hidden" class="actual-combo-count" name="actual_combo_count" @if(old('actual_combo_count')!=null) value="{{old('actual_combo_count')}}"@else value="0" @endif>
                  </div>
                </div>
            @endif
                     <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Payment Mode</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="has-feedback">
                                <select name="payment_mode_id" class="form-control" id="OrderPaymentMode">
                                @foreach($payment_modes as $payment_mode)
                                <option value="{{$payment_mode->id}}">{{$payment_mode->name}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Payment Status</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="has-feedback">
                                <select name="payment_status" class="form-control">
                                    <option value="2">To Be Settled</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Source</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="has-feedback">
                                <select name="source_id" class="form-control">
                                @foreach($sources as $source)
                                <option @if(old('source_id')==$source->id) selected @else @if($source->id==5) selected="" @endif @endif value="{{$source->id}}">{{$source->name}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Resource Order No</label>
                        <div class="col-sm-10 col-md-8">
                          <input type="text" class="form-control" name="resource_order_no" value="{{old('resource_order_no')}}"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Special Instructions</label>
                        <div class="col-sm-10 col-md-8">
                          <input type="text" class="form-control" name="instruction" value="{{old('instruction')}}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Coupon Code</label>
                        <div class="col-sm-10 col-md-8">
                          <input type="text" class="form-control" name="coupon_code" />
                        </div>
                        <button id="apply-coupon-button">Apply Coupon</button>
                    </div>
                    <div class="form-group hidden">
                        <label class="col-sm-2 col-md-2 control-label">Order Status</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="has-feedback">
                                <select name="status" class="form-control" >
                                <option value="2" selected="">In Kitchen</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    @if($ngos->count()!=0)
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Ngo Name</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="has-feedback">
                                <select name="ngo_id" class="form-control ngo-id">
                                <option value="0" selected="">No</option>
                                @foreach($ngos as $ngo)
                                    <option value="{{$ngo->id}}" data-donation = "{{$ngo->default_donation_amount}}" @if(old('ngo_id')==$ngo->id) selected="" @endif>{{$ngo->name}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group ngo-donation" @if(old('ngo_id')==null || old('ngo_id')==0) style="display:none;" @endif>
                        <label class="col-sm-2 col-md-2 control-label">Donation amount</label>
                        <div class="col-sm-10 col-md-8">
                          <input type="number" class="form-control donation_amount" name="donation_amount" @if(old('donation_amount')!=null) value="{{old('donation_amount')}}" @else value="0" @endif />
                        </div>
                    </div>
                    @endif
                    <div class="form-group redeem-points-box" style="display:none;">
                        <div class="col-sm-offset-2 col-sm-10 col-md-offset-2 col-md-10">
                            <div class="checkbox">
                                <label>
                                    <input class="user-points" type="checkbox" name="redeem_points" value="1" /> Redeem Credits (<span class="user-points-value"></span>) Points <small>At Max 50% Points can be used</small>
                                </label>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" class="form-control" name="platform" required="" value="Quick Order"/>
                    <input type="hidden" class="form-control discount-value" name="discount" required="" value="0"/>
                    <input type="hidden" class="form-control" id="min-order-amount" name="min_order_amount" value="-1" />
                    <input type="hidden" class="form-control" id="total-items-quantity" name="total_items_quantity" />
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Comment</label>
                        <div class="col-sm-10 col-md-8">
                          <input type="text" class="form-control" name="comment" value="{{old('comment')}}" />
                        </div>
                    </div>
                    <div class="form-group form-actions">
                        <div class="col-sm-offset-2 col-sm-10">
                            <a href="{{URL::route('admin.orders')}}" class="btn btn-default">Back</a>
                            <button class="btn btn-success" id="order-button" >Confirm Order</button>
                            <button  class="btn btn-primary" id="calculate-order-button" >Calculate</button>
                        </div>
                    </div>

                </form>
                    
            </div>
        </div>

  @endsection

  @section('script')

  <script type="text/javascript">
        $(function () {

            Messenger.options = {
                extraClasses: 'messenger-fixed messenger-on-bottom messenger-on-right',
                theme: 'flat'
            }

            $('#get-phone-data').click(function(event) {
                    event.preventDefault();
                    $('.user-first-name').val('');
                    $('.user-last-name').val('');
                    $('.user-email').val('');
                    $(".user-address option").remove();
                    that = $(this);
                    phone = $('input.user-phone').val();

                    $.ajax({
                        url: '/admin/user/phone/' + phone
                    })
                    .done(function(data) {
                        if (data.no_data == 1) {

                        }
                    else
                    {
                        $('.user-first-name').val(data.first_name);
                        $('.user-last-name').val(data.last_name);
                        $('.user-email').val(data.email);
                        var user_points = data.loyalty_points;
                        if(user_points>0)
                        {
                            $('.redeem-points-box').show();
                            $('.user-points-value').text(user_points);
                        }
                        else
                        {
                            $('.redeem-points-box').hide();
                        }
                        var addresses_data = [];
                        addresses_data.push({
                          'id' : 'new',
                          'text' : 'Add an Address'
                        });
                        data.addresses.map(function(address){
                            addresses_data.push({
                                'id' : address['area_id'],
                                'text' : address['address']
                            });
                        });

                        $(".user-address").select2({
                            data: addresses_data
                        });
                    }
                    Messenger().post({
                                message: data.message,
                                type: data.message_type,
                                showCloseButton: true
                            });
                });
            });

           
        });

$('#add-meal').click(function(event) {
  event.preventDefault();
    options = $('.combo-meals-options').html();
    count = $('.meal-count').val();
    actual_meal_count = $('.actual-meal-count').val();

    html = '  <div class="input select add-new-combo-meal"><label for="OrderMealId1">Meal</label>';
    html += '<select name="meals[' + count + '][id]" class="meals_dp" id="OrderMealId1">';
    html += options;
    html += '</select>';
    html += '<label for="OrderQuantity1">Quantity</label>';
    html += ' <input name="meals[' + count +'][quantity]" class="qty_dp" value="1" type="number" min="1" max="10" />';
    html += '<a class="remove-meal">Remove</a>';
    html += '<br></div>';

    $('.meal-count').val(++count);
    $('.actual-meal-count').val(++actual_meal_count);
    $('.combo-meals').append(html);

});

  $(document).on('click', '.remove-meal', function(event){
    $(this).parents('.add-new-combo-meal').remove();
    $('.actual-meal-count').val(--actual_meal_count);
  });

  $('#add-combo').click(function(event) {
  event.preventDefault();
    options = $('.combo-combos-options').html();
    count = $('.combo-count').val();
    actual_combo_count = $('.actual-combo-count').val();

    html = '  <div class="input select add-new-combo-combo"><label for="OrdercomboId1">combo</label>';
    html += '<select name="combos[' + count + '][id]" class="combos_dp" >';
    html += options;
    html += '</select>';
    html += '<label for="OrderQuantity1">Quantity</label>';
    html += ' <input name="combos[' + count +'][quantity]" class="qty_dp" value="1" type="number" min="1" max="10" />';
    html += '<a class="remove-combo">Remove</a>';
    html += '<br></div>';

    $('.actual-combo-count').val(++actual_combo_count);
    $('.combo-count').val(++count);
    $('.combo-combos').append(html);
});

  $(document).on('click', '.remove-combo', function(event){
    $(this).parents('.add-new-combo-combo').remove();
    $('.actual-combo-count').val(--actual_combo_count);
  });

  $(".user-area").select2({
      allowClear: true,
      placeholder: "Select a location",
  });

    $(".user-address").select2({
        placeholder: "Select a location",
        allowClear: true,
        val:null,
        tags : true
    });


        $('#order-button').click(function(event) {
    /* Act on the event */
    event.preventDefault();
    if ($(".actual-meal-count").val() > 0 || $(".actual-combo-count").val() > 0) {
    calculate();
    var total_order_amount = $("#total-amount strong").text();
      total_order_amount = parseFloat(total_order_amount);
    var min_order_amount = $("#min-order-amount").val();
      min_order_amount = parseFloat(min_order_amount);
    var total_items_quantity = $('#total-items-quantity');
        total_items_quantity = parseFloat(total_items_quantity);
        if(min_order_amount==-1)
        {
            Messenger().post({
                message: "Order cannot be placed.Select Area.",
                type: "error",
                showCloseButton: true
            });
        }
        else if(total_items_quantity>10)
        {
            Messenger().post({
                message: "Order cannot be placed.Total number items should be less than 10.",
                type: "error",
                showCloseButton: true
            });
        }
        else if(total_order_amount>min_order_amount)
        $("#quick-order-form").submit();
      else
      {
        if(isNaN(min_order_amount))
        {
        Messenger().post({
              message: "Select a Locality",
              type: "error",
              showCloseButton: true
          });
      }
      else
      {
        Messenger().post({
              message: "Order cannot be placed.Minimum Order Amount is "+min_order_amount,
              type: "error",
              showCloseButton: true
          });
      }
      }
    }
    else {
        Messenger().post({
              message: "Add At Least One Meal or Combo",
              type: "error",
              showCloseButton: true
          });
    }
  });

  function calculate()
  {
    isValid = true;

    var total_amount = 0;
    var total_quantity = 0;
    var points_used = 0;
      if ($('input.user-points').is(':checked'))
      {
          var points_used = parseFloat($('.redeem-points-box .user-points-value').text());
      }
    var discount = parseFloat($('.discount-value').val());

    // get meal total 
    var meals =  $('.add-new-combo-meal');

    var service_tax = parseFloat($('.user-area option:selected').data('servicetax'));
      var vat = parseFloat($('.user-area option:selected').data('vat'));
      var delivery_charge = parseFloat($('.user-area option:selected').data('deliverycharge'));
      var service_charge = parseFloat($('.user-area option:selected').data('servicecharge'));
      var packaging_charge = parseFloat($('.user-area option:selected').data('packagingcharge'));

    meals.map(function(index, meal){
        total_amount += $(meal).find(' select option:selected').data('amount') * $(meal).find('.qty_dp').val();
        total_quantity += parseInt($(meal).find('.qty_dp').val());
    });

    // get combo total
    var combos = $('.add-new-combo-combo');

    combos.map(function(index, combo){
        total_amount += $(combo).find(' select option:selected').data('amount') * $(combo).find('.qty_dp').val();
        total_quantity += parseInt($(combo).find('.qty_dp').val());
    });


    // add donation
    var donation = parseFloat($('.donation_amount').val());

    if(donation==null || isNaN(donation))
        donation = 0;
    var order_amount = total_amount;
    total_amount -= discount;
      if(points_used>total_amount)
      {
          points_used = total_amount;
      }
      total_amount -= points_used;
      if(total_amount<0)
      {
          total_amount=0;
      }
    var totalTax = service_tax * total_amount / 100;
      totalTax += (vat * total_amount/100);

      totalTax += delivery_charge+service_charge+packaging_charge;


    $('#total-amount strong').text(order_amount.toFixed(2));
    $('#total-tax strong').text(totalTax.toFixed(2));
    $('#total-item').text(total_quantity);
    $('#total-items-quantity').val(total_quantity);
    $('#total-discount strong').text(discount.toFixed(2));
    $('#total-order-amount strong').text((total_amount+totalTax).toFixed(2));
      $('#total-points-used strong').text(points_used.toFixed(2));
      $('#total-donation-amount strong').text(donation.toFixed(2));
  }

  $('#calculate-order-button').click(function(event) {
    event.preventDefault();

    calculate();


  });

  // apply coupon
  $('#apply-coupon-button').click(function(event){
    event.preventDefault();
    form = $(this).parents('form');
    $.ajax({
        url: '/admin/order/apply-coupon',
        data : form.serialize()
    })
    .done(function(data) {
        Messenger().post({
          message: data.message,
          type: data.message_type,
          showCloseButton: true
                  });
        $('.discount-value').val(data.discount);
        if(data.discount>0)
        {
          Messenger().post({
          message: 'With this Coupon : Discount '+data.discount,
          type: 'success',
          showCloseButton: true
                  });
        }
        if(data.cashback>0)
        {
          Messenger().post({
          message: 'With this Coupon : Cashback '+data.cashback,
          type: 'success',
          showCloseButton: true
                  });
        }
        var items = data.items;
        for(var i in items){
          Messenger().post({
          message: 'With this Coupon : '+items[i].name +' Quantity : '+ items[i].quantity,
          type: 'success',
          showCloseButton: true
                  });
        }
    })
   
    calculate();
  });
        $('.user-area').change(function(){
            $('#min-order-amount').val($('.user-area option:selected').data('minorderamount'));
        });

        $('.ngo-id').change(function(){
            if($(this).val()==0)
            {
                $('.ngo-donation').hide();
                $('.ngo-donation input').val(0);
            }
            else
            {
                $('.ngo-donation').show();
                $('.ngo-donation input').val($('.ngo-id option:selected').data('donation'));
            }
        });



        $('.user-address').change(function(){
            if($(this).val()=="new")
            {
                $('.add-new-address').attr("readonly",false);
                $(".user-area").attr("disabled",false);
                $('.add-new-address').text('');
            }
            else
            {
                $('.add-new-address').attr("readonly",true);
                $(".user-area").attr("disabled",true);
                var user_address = $('.user-address option:selected').text();
                $('.add-new-address').text(user_address);
                $(".user-area").select2("val",$('.user-address option:selected').val());
                $('#user-address-area-id').val($('.user-address option:selected').val());
            }
        });
    </script>

  @endsection