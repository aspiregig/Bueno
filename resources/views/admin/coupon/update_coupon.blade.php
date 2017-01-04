
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
          Update Coupon
        </div>
      </div>

      <div class="content-wrapper">
      @include('admin.partials.errors')
      @include('admin.partials.flash')
        <form id="new-product" class="form-horizontal" method="post" action="#" role="form">
              {{ csrf_field() }}
            {{ method_field('PATCH') }}
            <input type="hidden" name="id" value="{{$coupon->id}}">
            <legend>Checks</legend>
            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">Minimum Order Amount</label>
                <div class="col-sm-10 col-md-8">
                    <input type="number" class="form-control" name="min_order_amount" min="1" @if(old('min_order_amount')) value="{{old('min_order_amount')}}" @else value="{{$coupon_attributes['min_order_amount']}}" @endif/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">Minimum Quantity</label>
                <div class="col-sm-10 col-md-8">
                    <input type="number" class="form-control" name="min_quantity" min="1"  @if(old('min_quantity')) value="{{old('min_quantity')}}" @else value="{{$coupon_attributes['min_quantity']}}" @endif/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">Kitchens</label>
                <div class="col-sm-10 col-md-8">
                    <select multiple="multiple" class="GroupGroup form-control" name="kitchens[]">
                        @foreach($kitchens as $kitchen)
                            <option value="{{$kitchen->id}}" @if(in_array($kitchen->id,$coupon_attributes['on_kitchens'])) selected="" @endif>{{$kitchen->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 col-md-2 control-label">Payment Gateway</label>
                <div class="col-sm-10 col-md-8">
                    <select class="form-control GroupGroup"  id="availability" multiple="multiple" name="payment_modes[]">
                        @foreach($payment_modes as $payment)
                            <option value="{{$payment->id}}"  @if(in_array($payment->id,$coupon_attributes['payment_modes'])) selected="" @endif>{{$payment->name}}</option>
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
                                            <option value="{{$area->id}}" @if(in_array($area->id,$coupon_attributes['areas'])) selected @endif>{{$area->name}},{{$city->name}}</option>
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
                        <select name="cities[]" multiple="multiple" class="GroupGroup user-area form-control user-address-search">
                            @foreach($states as $state)
                                <optgroup label="{{$state->name}}">
                                    @foreach($state->cities as $city)
                                        <option value="{{$city->id}}" @if(in_array($city->id,$coupon_attributes['cities'])) selected @endif>{{$city->name}}</option>
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
                    <select multiple="multiple" class="GroupGroup form-control" name="states[]">
                        @foreach($states as $state)
                            <option value="{{$state->id}}" @if(in_array($state->id,$coupon_attributes['states'])) selected @endif>{{$state->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">Users</label>
                <div class="col-sm-10 col-md-8">
                    <select multiple="multiple" class="GroupGroup user-list form-control" name="users[]">
                        @if(count($coupon_attributes['users']))
                        @foreach($coupon_attributes['users'] as $user)
                            <option value="{{$user['id']}}" selected>{{$user['name']}}</option>
                        @endforeach
                            @endif
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">Admins who can apply</label>
                <div class="col-sm-10 col-md-8">
                    <select multiple="multiple" class="GroupGroup admin-list form-control" name="admins[]" >
                        @if(count($coupon_attributes['admins']))
                            @foreach($coupon_attributes['admins'] as $user)
                                <option value="{{$user['id']}}"selected>{{$user['name']}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">Nth Order</label>
                <div class="col-sm-10 col-md-8">
                    <input type="text" class="form-control" name="value_n" value="{{$coupon_attributes['nth_order']}}"/>
                </div>
            </div>
            <div class="well">
                <div class="form-group">
                    <label class="col-sm-2 col-md-2 control-label">Category</label><?php $category_count = 0;?>
                    <div class="col-sm-10 col-md-8">
                        @if(count($coupon_attributes['categories']))
                        @foreach($coupon_attributes['categories'] as $coupon_cat)
                        <div class="input select add-new-meal-category"><label>Category</label>
                            <select name="categories[{{$category_count}}][id]" class="categories_dp">
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}" @if($coupon_cat['id']==$category->id) selected @endif>{{$category->name}}</option>
                                @endforeach
                            </select>
                            <label>Quantity</label>
                            <input name="categories[{{$category_count++}}][quantity]" class="qty_dp" type="number" min="1" value="{{$coupon_cat['quantity']}}"/>
                                    <a class="remove-category">Remove</a>
                        </div>
                        @endforeach
                        @endif
                        @if($categories->count()!=0)
                        <div class="form-group">
                        <div>
                               <div class="control-group meal-category">
                                  <div class="meals-category-options hidden">
                                        @foreach($categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                      @endforeach
                                  </div>
                               </div>
                        </div>
                        </div>
                    @endif
                    </div>
                </div>
                <input type="hidden" class="category-count" name="category_count" value="{{$category_count}}">
                    <a href="#" class="btn btn-danger btn-xs" id="add-category">add a category</a>
            </div>
            <div class="well">
                <div class="form-group">
                    <label class="col-sm-2 col-md-2 control-label">Cuisime</label><?php $cuisine_count = 0;?>
                    <div class="col-sm-10 col-md-8">
                        @if(count($coupon_attributes['cuisines']))
                        @foreach($coupon_attributes['cuisines'] as $coupon_cat)
                        <div class="input select add-new-meal-cuisine"><label>Cuisime</label>
                            <select name="cuisines[{{$cuisine_count}}][id]" class="cuisines_dp">
                                @foreach($cuisines as $cuisine)
                                    <option value="{{$cuisine->id}}" @if($coupon_cat['id']==$cuisine->id) selected @endif>{{$cuisine->name}}</option>
                                @endforeach
                            </select>
                            <label>Quantity</label>
                            <input name="cuisines[{{$cuisine_count++}}][quantity]" class="qty_dp" type="number" min="1" value="{{$coupon_cat['quantity']}}"/>
                                    <a class="remove-cuisine">Remove</a>
                        </div>
                        @endforeach
                        @endif
                        @if($cuisines->count()!=0)
                        <div class="form-group">
                        <div>
                               <div class="control-group meal-cuisine">
                                  <div class="meals-cuisine-options hidden">
                                        @foreach($cuisines as $cuisine)
                                        <option value="{{$cuisine->id}}">{{$cuisine->name}}</option>
                                      @endforeach
                                  </div>
                               </div>
                        </div>
                        </div>
                    @endif
                    </div>
                </div>
                <input type="hidden" class="cuisine-count" name="cuisine_count" value="{{$cuisine_count}}">
                    <a href="#" class="btn btn-danger btn-xs" id="add-cuisine">add a cuisine</a>
            </div>
            <div class="well">
                <div class="form-group">
                    <label class="col-sm-2 col-md-2 control-label">Meal(s) and Combo(s) Applicable For</label><?php $item_count = 0;?>
                    <div class="col-sm-10 col-md-8">
                        @if(count($coupon_attributes['items']))
                        @foreach($coupon_attributes['items'] as $coupon_cat)
                        <div class="input select add-new-meal-item"><label>item</label>
                            <select name="meals[{{$item_count}}][id]" class="items_dp">
                                @foreach($items as $item)
                                    @if($item->itemable_type!='App\Models\Goody')
                                    <option value="{{$item->id}}" @if($coupon_cat['id']==$item->id) selected @endif>{{$item->itemable->name}}
                                    </option>
                                    @endif
                                @endforeach
                            </select>
                            <label>Quantity</label>
                            <input name="meals[{{$item_count++}}][quantity]" class="qty_dp" type="number" min="1" value="{{$coupon_cat['quantity']}}"/>
                                    <a class="remove-item">Remove</a>
                        </div>
                        @endforeach
                        @endif
                        @if($items->count()!=0)
                        <div class="form-group">
                        <div>
                               <div class="control-group meal-item">
                                  <div class="meals-give-item-options hidden">
                                        @foreach($items as $item)
                                        <option value="{{$item->id}}">{{$item->itemable->name}}</option>
                                      @endforeach
                                  </div>
                               </div>
                        </div>
                        </div>
                    @endif
                    </div>
                </div>
                <input type="hidden" class="item-count" name="item_count" value="{{$item_count}}">
                    <a href="#" class="btn btn-danger btn-xs" id="add-item">add a item</a>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">From</label>
                <div class="input-group input-group-sm " style="max-width: 300px;padding-left: 14px;">
                <span class="input-group-addon" >
                  <i class="fa fa-calendar-o"></i>
                </span>
                    <input name="start" type="text" class="form-control datepicker" value="{{$coupon_attributes['from']}}" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">To</label>
                <div class="input-group input-group-sm " style="max-width: 300px;padding-left: 14px;">
                <span class="input-group-addon" >
                  <i class="fa fa-calendar-o"></i>
                </span>
                    <input name="end" type="text" class="form-control datepicker" value="{{$coupon_attributes['to']}}" />
                </div>
            </div>
            <legend>Gives</legend>
            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">Discount Percent</label>
                <div class="col-sm-10 col-md-8">
                    <input type="text" class="form-control" name="discount_percent_value" value="{{$coupon_attributes['discount_percent']}}"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">Discount Amount</label>
                <div class="col-sm-10 col-md-8">
                    <input type="text" class="form-control" name="discount_amount_value" value="{{$coupon_attributes['discount_value']}}"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">Maximum Discount Amount</label>
                <div class="col-sm-10 col-md-8">
                    <input type="text" class="form-control" name="max_discount_amount" value="{{$coupon_attributes['max_discount_amount']}}"/>
                </div>
            </div>
            <div class="well">
                <div class="form-group">
                    <label class="col-sm-2 col-md-2 control-label">Goody(s) , Meal(s) or Combo(s) With this Coupon</label><?php $item_give_count = 0;?>
                    <div class="col-sm-10 col-md-8">
                        @if(count($coupon_attributes['give_items']))
                        @foreach($coupon_attributes['give_items'] as $coupon_cat)
                        <div class="input select add-new-meal-item-give"><label>Item</label>
                            <select name="give_meals[{{$item_give_count}}][id]" class="item_gives_dp">
                                @foreach($all_items as $item)
                                    <option value="{{$item->id}}" @if($coupon_cat['id']==$item->id) selected @endif>{{$item->itemable->name}}</option>
                                @endforeach
                            </select>
                            <label>Quantity</label>
                            <input name="give_meals[{{$item_give_count++}}][quantity]" class="qty_dp" type="number" min="1" value="{{$coupon_cat['quantity']}}"/>
                                    <a class="remove-item-give">Remove</a>
                        </div>
                        @endforeach
                        @endif
                        @if($items->count()!=0)
                        <div class="form-group">
                        <div>
                               <div class="control-group meal-item-give">
                                  <div class="meals-item-options hidden">
                                        @foreach($items as $item)
                                        <option value="{{$item->id}}">{{$item->itemable->name}}</option>
                                      @endforeach
                                  </div>
                               </div>
                        </div>
                        </div>
                    @endif
                    </div>
                </div>
                <input type="hidden" class="item-give-count" name="item_give_count" value="{{$item_give_count}}">
                    <a href="#" class="btn btn-danger btn-xs" id="add-item-give">add a item</a>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">Credits Percent Value</label>
                <div class="col-sm-10 col-md-8">
                    <input type="text" class="form-control" name="cashback_percent_value" value="{{$coupon_attributes['cashback_percent_value']}}"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">Credits Absolute Value</label>
                <div class="col-sm-10 col-md-8">
                    <input type="text" class="form-control" name="points_value" value="{{$coupon_attributes['points_value']}}" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">Maximum Cashback Amount</label>
                <div class="col-sm-10 col-md-8">
                    <input type="text" class="form-control" name="max_cashback_amount" value="{{$coupon_attributes['max_cashback_amount']}}"/>
                </div>
            </div>
            <legend>Coupon Details</legend>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Coupon Code</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" name="code" value="{{$coupon->code}}" readonly />
              </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">Offer Text</label>
                <div class="col-sm-10 col-md-8">
                    <input type="text" class="form-control" name="offer_text" value="{{$coupon->offer_text }}"/>
                </div>
            </div>
            
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Description</label>
              <div class="col-sm-10 col-md-8">
              <textarea class="form-control" name="description">{{$coupon->description}}</textarea>
              </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 col-md-2 control-label">Coupon Status</label>
                <div class="col-sm-10 col-md-8">
                    <select class="form-control"  name="status" >
                        <option value="1" @if($coupon->status) selected="" @endif>Active</option>
                        <option value="0" @if(!$coupon->status) selected="" @endif>Disabled</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 col-md-offset-2 col-md-10">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="one_time" value="1" @if($coupon_attributes['one_time']==1) checked  @endif/>One Time
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 col-md-offset-2 col-md-10">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="android" value="1" @if($coupon_attributes['android']==1) checked  @endif />Android
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 col-md-offset-2 col-md-10">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="quick" value="1" @if($coupon_attributes['quick']==1) checked  @endif />Quick Order
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 col-md-offset-2 col-md-10">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="ios" value="1" @if($coupon_attributes['ios']==1) checked  @endif />IOS
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 col-md-offset-2 col-md-10">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="web" value="1" @if($coupon_attributes['web']==1) checked  @endif />Web
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 col-md-offset-2 col-md-10">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="is_offer" value="1"  @if($coupon->is_offer==1) checked  @endif />Is Offer
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group form-actions">
              <div class="col-sm-offset-2 col-sm-10 col-md-offset-2 col-md-10">
                <a href="{{URL::route('admin.coupons')}}" class="btn btn-default">Back</a>
                  <button type="submit" class="btn btn-success">Update Coupon </button>
                  <a href="#" data-toggle="modal" data-target="#confirm-modal" class="btn btn-danger">Delete</a>
              </div>
            </div>
        </form>
      </div>
                                   <!-- Confirm Modal -->
  <div class="modal fade" id="confirm-modal" tabindex="-1" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <form method="get" action="#" role="form">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">
                  Are you sure you want to delete this Coupon?
                </h4>
              </div>
              <div class="modal-body">
            Do you want to delete this Coupon? All the info will be erased.
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a href="{{URL::route('admin.delete_coupon',$coupon->id)}}"  class="btn btn-danger">Yes, delete it</a>
              </div>
          </form>
        </div>
      </div>
  </div>
    </div>

  @endsection

  @section('script')

  <script type="text/javascript">
    $(function () {

        // Datepicker
        $('.datepicker').datetimepicker({
            autoclose: true,
            dateFormat: 'dd-mm-yy',
            orientation: 'left bottom'
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
      // Form validation
      $('#new-product').validate({
        rules: {
          
        },
        highlight: function (element) {
          $(element).closest('.form-group').removeClass('success').addClass('error');
        },
        success: function (element) {
          element.addClass('valid').closest('.form-group').removeClass('error').addClass('success');
        }
      });

          // Minicolors colorpicker
          $('input.minicolors').minicolors({
            position: 'top left',
            defaultValue: '#9b86d1',
            theme: 'bootstrap'
          });
    });

  $('.GroupGroup').select2();



  //Categories


  $('#add-category').click(function(event) {
  event.preventDefault();
    options = $('.meals-category-options').html();
    count = $('.category-count').val();

    html = '  <div class="input select add-new-meal-category"><label for="OrderMealId1">Category</label>';
    html += '<select name="categories[' + count + '][id]" class="categories_dp" id="OrderMealId1">';
    html += options;
    html += '</select>';
    html += '<label for="OrderQuantity1"> Minimum Quantity</label>';
    html += ' <input name="categories[' + count +'][quantity]" class="qty_dp" value="1" type="number" min="1" id="OrderQuantity1" />';
    html += '<a class="remove-category">Remove</a>';
    html += '<br></div>';
    console.log($('.category-count').val());
    $('.category-count').val(++count);
    $('.meal-category').append(html);
});

  $(document).on('click', '.remove-category', function(event){
    $(this).parents('.add-new-meal-category').remove();
  });

  //CUisine

  $('#add-cuisine').click(function(event) {
  event.preventDefault();
    options = $('.meals-cuisine-options').html();
    count = $('.cuisine-count').val();

    html = '  <div class="input select add-new-meal-cuisine"><label for="OrderMealId1">Cuisine</label>';
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

  $('#add-item').click(function(event) {
  event.preventDefault();
    options = $('.meals-item-options').html();
    count = $('.item-count').val();

    html = '  <div class="input select add-new-meal-item"><label for="OrderMealId1">Item</label>';
    html += '<select name="meals[' + count + '][id]" class="categories_dp" id="OrderMealId1">';
    html += options;
    html += '</select>';
    html += '<label for="OrderQuantity1">Quantity</label>';
    html += ' <input name="meals[' + count +'][quantity]" class="qty_dp" value="1" type="number" min="1" />';
    html += '<a class="remove-item">Remove</a>';
    html += '<br></div>';

    $('.item-count').val(++count);
    $('.meal-item').append(html);
});

  $(document).on('click', '.remove-item', function(event){
    $(this).parents('.add-new-meal-item').remove();
  });


  //Give

  $('#add-item-give').click(function(event) {
  event.preventDefault();
    options = $('.meals-give-item-options').html();
    count = $('.item-give-count').val();

    html = '  <div class="input select add-new-meal-item-give"><label for="OrderMealId1">Item</label>';
    html += '<select name="give_meals[' + count + '][id]" class="categories_dp" id="OrderMealId1">';
    html += options;
    html += '</select>';
    html += '<label for="OrderQuantity1">Quantity</label>';
    html += ' <input name="give_meals[' + count +'][quantity]" class="qty_dp" value="1" type="number" min="1" id="OrderQuantity1" />';
    html += '<a class="remove-item">Remove</a>';
    html += '<br></div>';

    $('.item-give-count').val(++count);
    $('.meal-item-give').append(html);
});

  $(document).on('click', '.remove-item-give', function(event){
    $(this).parents('.add-new-meal-item-give').remove();
  });


  </script>

  @endsection