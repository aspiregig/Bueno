@extends('admin.master')

  @section('title') Bueno Kitchen @endsection

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
      <div class="menubar">
        <div class="sidebar-toggler visible-xs">
          <i class="ion-navicon"></i>
        </div>

        <div class="page-title">
          Add new Coupon
        </div>
      </div>
      
      <div class="content-wrapper">
          @include('admin.partials.errors')
          @include('admin.partials.flash')
        <form id="new-product" class="form-horizontal" method="post" role="form">
          <legend>Checks</legend>
                    {{ csrf_field() }}
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Minimum Order Amount</label>
              <div class="col-sm-10 col-md-8">
                <input type="number" class="form-control" name="min_order_amount" min="1" value="{{old('min_order_amount')}}" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Minimum Quantity</label>
              <div class="col-sm-10 col-md-8">
                <input type="number" class="form-control" name="min_quantity" min="1" value="{{old('min_quantity')}}"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Kitchens</label>
              <div class="col-sm-10 col-md-8">
                <select multiple="multiple" class="GroupGroup form-control" name="kitchens[]" >
                  @foreach($kitchens as $kitchen)
                  <option value="{{$kitchen->id}}" @if(old('kitchens')!=null) @if(in_array($kitchen->id,old('kitchens'))) selected="" @endif @endif>{{$kitchen->name}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 col-md-2 control-label">Payment Gateway</label>
                <div class="col-sm-10 col-md-8">
                    <select class="form-control GroupGroup"  id="availability" multiple="multiple" name="payment_modes[]">
                        @foreach($payment_modes as $payment)
                            <option value="{{$payment->id}}" @if(old('payment_modes')!=null) @if(in_array($payment->id,old('payment_modes'))) selected="" @endif @endif>{{$payment->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">Areas</label>
                <div class="col-sm-10 col-md-8">
                    <div class="has-feedback">
                        <select name="areas[]" multiple="multiple" class="GroupGroup user-area form-control user-address-search" >
                        @foreach($states as $state)
                        <optgroup label="{{$state->name}}">
                        @foreach($state->cities as $city)
                            @foreach($city->areas as $area)
                              <option value="{{$area->id}}" @if(old('areas')!=null) @if(in_array($area->id,old('areas'))) selected="" @endif @endif>{{$area->name}},{{$city->name}}</option>
                            @endforeach
                        @endforeach
                        </optgroup>
                        @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">Cities</label>
                <div class="col-sm-10 col-md-8">
                    <div class="has-feedback">
                        <select name="cities[]" multiple="multiple" class="GroupGroup user-area form-control user-address-search" >
                        @foreach($states as $state)
                        <optgroup label="{{$state->name}}">
                        @foreach($state->cities as $city)
                              <option value="{{$city->id}}" @if(old('cities')!=null) @if(in_array($city->id,old('cities'))) selected="" @endif @endif>{{$city->name}}</option>
                        @endforeach
                        </optgroup>
                        @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">States</label>
              <div class="col-sm-10 col-md-8">
                <select multiple="multiple" class="GroupGroup form-control" name="states[]" >
                  @foreach($states as $state)
                  <option value="{{$state->id}}" @if(old('states')!=null) @if(in_array($state->id,old('states'))) selected="" @endif @endif>{{$state->name}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">Users</label>
                <div class="col-sm-10 col-md-8">
                    <select multiple="multiple" class="user-list form-control" name="users[]" >
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">Admins who can apply</label>
                <div class="col-sm-10 col-md-8">
                    <select multiple="multiple" class="admin-list form-control" name="admins[]" >
                    </select>
                </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Nth Order</label>
              <div class="col-sm-10 col-md-8">
                <input type="number" class="form-control" name="value_n" value="{{old('value_n')}}" />
              </div>
            </div>
            @if($categories->count()!=0)
            <div class="well">
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Category</label>
              <div class="col-sm-10 col-md-8">
                       <div class="control-group meal-category">
                          <div class="meals-category-options hidden">
                                @foreach($categories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                              @endforeach
                          </div>
                          <a href="#" class="btn btn-danger btn-xs" id="add-category">add a category</a>
                       </div>
                      <input type="hidden" class="category-count" name="category_count" @if(old('category_count')) value="{{old('category_count')}}"@else value="0" @endif>
                  </div>
              </div>
            </div>
            @endif
            @if($cuisines->count()!=0)
            <div class="well">
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Cuisine</label>
              <div class="col-sm-10 col-md-8">
                       <div class="control-group meal-cuisine">
                          <div class="meals-cuisine-options hidden">
                                @foreach($cuisines as $cuisine)
                                <option value="{{$cuisine->id}}">{{$cuisine->name}}</option>
                              @endforeach
                          </div>
                          <a href="#" class="btn btn-danger btn-xs" id="add-cuisine">add a cuisine</a>
                       </div>
                      <input type="hidden" class="cuisine-count" value="0">
                  </div>
              </div>
            </div>
            @endif
            @if($meals->count()!=0)
            <div class="well">
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Meal(s) in Order</label>
              <div class="col-sm-10 col-md-8">
                       <div class="control-group combo-meals">
                          <div class="combo-meals-options hidden">
                                @foreach($meals as $meal)
                                <option value="{{$meal->id}}">{{$meal->name}}</option>
                              @endforeach
                          </div>
                          <a href="#" class="btn btn-danger btn-xs" id="add-meal">add a meal</a>
                       </div>
                      <input type="hidden" class="meal-count" value="0">
                  </div>
              </div>
            </div>
                @endif
            @if($combos->count()!=0)

            <div class="well">
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Combo(s) in Order</label>
              <div class="col-sm-10 col-md-8">
                       <div class="control-group combo-combos">
                          <div class="combo-combos-options hidden">
                                @foreach($combos as $combo)
                                <option value="{{$combo->id}}">{{$combo->name}}</option>
                              @endforeach
                          </div>
                          <a href="#" class="btn btn-danger btn-xs" id="add-combo">add a combo</a>
                       </div>
                      <input type="hidden" class="combo-count" value="0">
                  </div>
             </div>
           </div>
            @endif

            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">From</label>
              <div class="input-group input-group-sm " style="max-width: 300px;padding-left: 14px;">
                <span class="input-group-addon" >
                  <i class="fa fa-calendar-o"></i>
                </span>
                <input name="start" type="text" class="form-control from-datepicker" value="{{old('start')}}" />
              </div>  
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">To</label>
              <div class="input-group input-group-sm" style="max-width: 300px;padding-left: 14px;">
                <span class="input-group-addon" >
                  <i class="fa fa-calendar-o"></i>
                </span>
                <input name="end" type="text" class="form-control to-datepicker" value="{{old('end')}}"/>
            </div>
            </div>

          <legend>Gives</legend>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Discount Percent</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" name="discount_percent_value" value="{{old('discount_percent_value')}}"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Discount Amount</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" name="discount_amount_value" value="{{old('discount_amount_value')}}"/>
              </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">Maximum Discount Amount</label>
                <div class="col-sm-10 col-md-8">
                    <input type="number" class="form-control" name="max_discount_amount" min="1" value="{{old('max_discount_amount')}}"/>
                </div>
            </div>
            @if($goodies->count()!=0)
                  <div class="well">
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Goody(s) </label>
              <div class="col-sm-10 col-md-8">
                       <div class="control-group goody-goodies">
                          <div class="goody-goodies-options hidden">
                              @foreach($goodies as $goody)
                                <option value="{{$goody->id}}">{{$goody->name}}</option>
                              @endforeach
                          </div>
                          <a href="#" class="btn btn-danger btn-xs" id="add-goody">add a goody</a>
                       </div>
                      <input type="hidden" class="goody-count" value="0">
                  </div>
               </div>
             </div>
            @endif
            @if($meals->count()!=0)
            <div class="well">
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Meal(s) add to Order</label>
              <div class="col-sm-10 col-md-8">
                       <div class="control-group meal-gives">
                          <div class="meal-gives-options hidden">
                                @foreach($meals as $meal)
                                <option value="{{$meal->id}}">{{$meal->name}}</option>
                              @endforeach
                          </div>
                          <a href="#" class="btn btn-danger btn-xs" id="add-give-meal">add a meal</a>
                       </div>
                      <input type="hidden" class="give-meal-count" value="0">
                      </div>
                  </div>
            </div>
            @endif
            @if($combos->count()!=0)
            <div class="well">
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Combo(s) add to Order</label>
              <div class="col-sm-10 col-md-8">
                       <div class="control-group combo-gives">
                          <div class="combo-gives-options hidden">
                                @foreach($combos as $combo)
                                <option value="{{$combo->id}}">{{$combo->name}}</option>
                              @endforeach
                          </div>
                          <a href="#" class="btn btn-danger btn-xs" id="add-give-combo">add a combo</a>
                       </div>
                      <input type="hidden" class="give-combo-count" value="0">
                  </div>
                </div>
             </div>
            @endif
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Credits Percent Value</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" name="cashback_percent_value" value="{{old('cashback_percent_value')}}"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Credits Absolute Value</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" name="points_value" value="{{old('points_value')}}"/>
              </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">Maximum Cashback Amount</label>
                <div class="col-sm-10 col-md-8">
                    <input type="number" class="form-control" name="max_cashback_amount" min="1" value="{{old('max_cashback_amount')}}"/>
                </div>
            </div>
          <legend>Coupon Details</legend>
              <div class="form-group">
                  <label class="col-sm-2 col-md-2 control-label">Coupon Code</label>
                  <div class="col-sm-10 col-md-8">
                    <input type="text" class="form-control" name="coupon_code" required="" value="{{old('coupon_code')}}"/>
                  </div>
              </div>

            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">Offer Text</label>
                <div class="col-sm-10 col-md-8">
                    <input type="text" class="form-control" name="offer_text" value="{{ old('offer_text')  }}"/>
                </div>
            </div>
              <div class="form-group">
                  <label class="col-sm-2 col-md-2 control-label">Description</label>
                  <div class="col-sm-10 col-md-8">
                    <textarea name="description" class="form-control" required="">{{old('description')}}</textarea>
                  </div>
              </div>
              <div class="form-group">
              <label for="inputPassword3" class="col-sm-2 col-md-2 control-label">Coupon Status</label>
              <div class="col-sm-10 col-md-8">
                <select class="form-control"  id="status" name="status" required="">
                  <option @if(old('status')==1) selected @endif value="1">Active</option>
                  <option @if(old('status')==0) selected @endif value="0">Disabled</option>
                </select>
              </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 col-md-offset-2 col-md-10">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="one_time" value="1" @if(old('one_time')==1) checked  @endif />One Time
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 col-md-offset-2 col-md-10">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="android" value="1" @if(old('android')==1) checked  @endif />Android
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 col-md-offset-2 col-md-10">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="quick" value="1" @if(old('quick')==1) checked  @endif />Quick Order
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 col-md-offset-2 col-md-10">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="ios" value="1" @if(old('ios')==1) checked  @endif />IOS
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 col-md-offset-2 col-md-10">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="web" value="1" @if(old('web')==1) checked  @endif />Web
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 col-md-offset-2 col-md-10">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="is_offer" value="1" @if(old('is_offer')==1) checked  @endif />Is Offer
                        </label>
                    </div>
                </div>
            </div>
              <div class="form-group form-actions">
                  <div class="col-sm-offset-2 col-sm-10 col-md-offset-2 col-md-10">
                    <a href="{{URL::route('admin.coupons')}}" class="btn btn-default">Cancel</a>
                      <button type="submit" class="btn btn-success">Create Coupon </button>
                  </div>
              </div>
        </form>
      </div>
    </div>

  @endsection

  @section('script')

  <script type="text/javascript">
    $(function () {

      // Form validation
      $('#new-product').validate({
        rules: {
          "product[first_name]": {
            required: true
          },
          "product[email]": {
            required: true,
            email: true
          },
          "product[address]": {
            required: true
          },
          "product[notes]": {
            required: true
          }
        },
        highlight: function (element) {
          $(element).closest('.form-group').removeClass('success').addClass('error');
        },
        success: function (element) {
          element.addClass('valid').closest('.form-group').removeClass('error').addClass('success');
        }
      });

      // Product tags with select2
      $("#product-tags").select2({
        placeholder: 'Select tags or add new ones',
        tags:["shirt", "gloves", "socks", "sweater"],
        tokenSeparators: [",", " "]
      });

      // Bootstrap wysiwyg
      $("#summernote").summernote({
        height: 240,
        toolbar: [
            ['style', ['style']],
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['fontsize', ['fontsize']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['insert', ['picture', 'link', 'video']],
            ['view', ['fullscreen', 'codeview']],
            ['table', ['table']],
        ]
      });

      // Datepicker
          $('.from-datepicker').datetimepicker({
            autoclose: true,
            orientation: 'left bottom',
            dateFormat: 'dd-mm-yy',
          startDate: new Date()
          });
        $('.to-datepicker').datetimepicker({
            autoclose: true,
            orientation: 'left bottom',
            dateFormat: 'dd-mm-yy',
            startDate: new Date()
        });

    });

  $('.GroupGroup').select2();
  $(".products").select2();

  $('#add-meal').click(function(event) {
  event.preventDefault();
    options = $('.combo-meals-options').html();
    count = $('.meal-count').val();

    html = '  <div class="input select add-new-combo-meal"><label for="OrderMealId1">Meal</label>';
    html += '<select name="meals[' + count + '][id]" class="meals_dp" id="OrderMealId1">';
    html += options;
    html += '</select>';
    html += '<label for="OrderQuantity1"> Minimum Quantity</label>';
    html += ' <input name="meals[' + count +'][quantity]" class="qty_dp" value="1" type="number" min="1" id="OrderQuantity1" />';
    html += '<a class="remove-meal">Remove</a>';
    html += '<br></div>';

    $('.meal-count').val(++count);
    $('.combo-meals').append(html);
});

  $(document).on('click', '.remove-meal', function(event){
    $(this).parents('.add-new-combo-meal').remove();
  });

  $('#add-combo').click(function(event) {
  event.preventDefault();
    options = $('.combo-combos-options').html();
    count = $('.combo-count').val();

    html = '  <div class="input select add-new-combo-combo"><label for="OrdercomboId1">combo</label>';
    html += '<select name="combos[' + count + '][id]" class="combos_dp" id="OrdercomboId1">';
    html += options;
    html += '</select>';
    html += '<label for="OrderQuantity1"> Minimum Quantity</label>';
    html += ' <input name="combos[' + count +'][quantity]" class="qty_dp" value="1" type="number" min="1" id="OrderQuantity1" />';
    html += '<a class="remove-combo">Remove</a>';
    html += '<br></div>';

    $('.combo-count').val(++count);
    $('.combo-combos').append(html);
});

$('#add-category').click(function(event) {
  event.preventDefault();
    options = $('.meals-category-options').html();
    count = $('.category-count').val();

    html = '  <div class="input select add-new-meal-category"><label for="OrderMealId1">Category</label>';
    html += '<select name="categories[' + count + '][id]" class="categories_dp">';
    html += options;
    html += '</select>';
    html += '<label for="OrderQuantity1"> Minimum Quantity</label>';
    html += ' <input name="categories[' + count +'][quantity]" class="qty_dp" value="1" type="number" min="1" id="OrderQuantity1" />';
    html += '<a class="remove-category">Remove</a>';
    html += '<br></div>';

    $('.category-count').val(++count);
    $('.meal-category').append(html);
});

  $(document).on('click', '.remove-category', function(event){
    $(this).parents('.add-new-meal-category').remove();
  });

  $('#add-cuisine').click(function(event) {
  event.preventDefault();
    options = $('.meals-cuisine-options').html();
    count = $('.cuisine-count').val();

    html = '  <div class="input select add-new-meal-cuisine"><label for="OrderMealId1">Meal</label>';
    html += '<select name="cuisines[' + count + '][id]" class="categories_dp" id="OrderMealId1">';
    html += options;
    html += '</select>';
    html += '<label for="OrderQuantity1">Quantity</label>';
    html += ' <input name="cuisines[' + count +'][quantity]" class="qty_dp" value="1" type="number" min="1" id="OrderQuantity1" />';
    html += '<a class="remove-cuisine">Remove</a>';
    html += '<br></div>';

    $('.cuisine-count').val(++count);
    $('.meal-cuisine').append(html);
});

  $(document).on('click', '.remove-cuisine', function(event){
    $(this).parents('.add-new-meal-cuisine').remove();
  });

//Gives

   $('#add-goody').click(function(event) {
  event.preventDefault();
    options = $('.goody-goodies-options').html();
    count = $('.goody-count').val();

    html = '  <div class="input select add-new-goody-goody"><label for="OrdercomboId1">goody</label>';
    html += '<select name="goodies[' + count + '][id]" class="goodies_dp" id="OrdercomboId1">';
    html += options;
    html += '</select>';
    html += '<label for="OrderQuantity1">Quantity</label>';
    html += ' <input name="goodies[' + count +'][quantity]" class="qty_dp" value="1" type="number" min="1" id="OrderQuantity1" />';
    html += '<a class="remove-goody">Remove</a>';
    html += '<br></div>';

    $('.goody-count').val(++count);
    $('.goody-goodies').append(html);
});


  $('#add-give-meal').click(function(event) {
  event.preventDefault();
    options = $('.meal-gives-options').html();
    count = $('.give-meal-count').val();

    html = '  <div class="input select add-new-give-meal"><label for="OrderMealId1">Meal</label>';
    html += '<select name="give_meals[' + count + '][id]" class="meals_dp" >';
    html += options;
    html += '</select>';
    html += '<label for="OrderQuantity1">Quantity</label>';
    html += ' <input name="give_meals[' + count +'][quantity]" class="qty_dp" value="1" type="number" min="1" id="OrderQuantity1" />';
    html += '<a class="remove-give-meal">Remove</a>';
    html += '<br></div>';

    $('.give-meal-count').val(++count);
    $('.meal-gives').append(html);
});

  $(document).on('click', '.remove-give-meal', function(event){
    $(this).parents('.add-new-give-meal').remove();
  });

  $('#add-give-combo').click(function(event) {
  event.preventDefault();
    options = $('.combo-gives-options').html();
    count = $('.give-combo-count').val();

    html = '  <div class="input select add-new-give-combo"><label >combo</label>';
    html += '<select name="give_combos[' + count + '][id]" class="combos_dp" >';
    html += options;
    html += '</select>';
    html += '<label for="OrderQuantity1">Quantity</label>';
    html += ' <input name="give_combos[' + count +'][quantity]" class="qty_dp" value="1" type="number" min="1"  />';
    html += '<a class="remove-give-combo">Remove</a>';
    html += '<br></div>';

    $('.give-combo-count').val(++count);
    $('.combo-gives').append(html);
});

  $(document).on('click', '.remove-give-meal', function(event){
    $(this).parents('.add-new-give-meal').remove();
  });

  $(document).on('click', '.remove-combo', function(event){
    $(this).parents('.add-new-combo-combo').remove();
  });

  $(document).on('click', '.remove-goody', function(event){
    $(this).parents('.add-new-goody-goody').remove();
  });

$(document).on('click', '.remove-give-combo', function(event){
    $(this).parents('.add-new-give-combo').remove();
});

  // SEARCH SELECT USER
$('.user-list').select2({
    placeholder: "Search Users",
    ajax: {
        url: "/admin/user/all",
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
                items.push({ "text" : data[i]['email'], "id" :data[i]['id'] });
            }
            // parse the results into the format expected by Select2.
            // since we are using custom formatting functions we do not need to
            // alter the remote JSON data
            return {
                results: items
            };
        },
        cache: true
    },
    escapeMarkup: function (markup) { return markup; },
    minimumInputLength: 1
});


    $('.admin-list').select2({
        placeholder: "Search Admins",
        ajax: {
            url: "/admin/admin/all",
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
                    items.push({ "text" : data[i]['email'], "id" :data[i]['id'] });
                }
                // parse the results into the format expected by Select2.
                // since we are using custom formatting functions we do not need to
                // alter the remote JSON data
                return {
                    results: items
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) { return markup; },
        minimumInputLength: 1
    });
  </script>

  @endsection