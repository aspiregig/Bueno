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
  @include('admin.partials.flash')
  @include('admin.partials.errors')
			<div class="menubar">
				<div class="sidebar-toggler visible-xs">
					<i class="ion-navicon"></i>
				</div>

				<div class="page-title">
					Add a New Combo
				</div>
			</div>

			<div class="content-wrapper">
				<form id="new-combo" class="form-horizontal" method="post" role="form" enctype="multipart/form-data">
                    {{ csrf_field() }}
				  	<div class="form-group">
					    <label class="col-sm-2 col-md-2 control-label">Combo Name</label>
					    <div class="col-sm-10 col-md-8">
					      <input type="text" class="form-control item-name" name="name" required="" value="{{old('name')}}"/>
					    </div>
				  	</div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Combo Alias</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control slug" name="slug" required="" value="{{old('slug')}}" readonly=""/>
              </div>
            </div>
          <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Product SKU</label>
              <div class="col-sm-10 col-md-8">
                  <input type="text" class="form-control" name="product_sku" required="" value="{{old('product_sku')}}"/>
              </div>
          </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Combo Serves</label>
              <div class="col-sm-10 col-md-8">
                <input type="number" class="form-control" name="serves" min="1" required="" value="{{old('serves')}}"/>
              </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">Combo Type :</label>
                <div class="col-sm-10 col-md-8">
                    <select class="form-control" name="type" required="">
                        @foreach($meal_types as $meal_type)
                            <option value="{{$meal_type->id}}" @if(old('type')==$meal_type->id) selected="" @endif>{{$meal_type->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 col-md-2 control-label">Cuisine</label>
                <div class="col-sm-10 col-md-8">
                    <select class="form-control" name="cuisine_id" required="">
                        @foreach($cuisines as $cuisine)
                            <option value="{{$cuisine->id}}" @if(old('cuisine_id')==$cuisine->id) selected="" @endif>{{$cuisine->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 col-md-2 control-label">Spice Level</label>
                <div class="col-sm-10 col-md-8">
                    <select class="form-control" name="spice_level" required="">
                        @foreach($spice_levels as $spice_level)
                            <option value="{{$spice_level->id}}" @if(old('spice_level')==$spice_level->id) selected="" @endif>{{$spice_level->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
              <label for="inputPassword3" class="col-sm-2 col-md-2 control-label">Category</label>
              <div class="col-sm-10 col-md-8">
                <select class="form-control GroupGroup" name="category_id" required="">
                @foreach($categories as $category)
                  <option value="{{$category->id}}" @if(old('category_id')==$category->id) selected="" @endif>{{$category->name}}</option>
                @endforeach
                </select>
              </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 col-md-2 control-label">Availability</label>
                <div class="col-sm-10 col-md-8">
                    <select class="form-control GroupGroup"  id="availability" multiple="multiple" name="availabilities[]" required="">
                        @foreach($availabilities as $availability)
                            <option value="{{$availability->id}}" @if(old('availabilities')!=null) @if(in_array($availability->id,old('availabilities'))) selected="" @endif @endif>{{$availability->name}}</option>
                        @endforeach

                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">Display picture</label>
                <div class="col-sm-10 col-md-8">
                    <div class="well">
                    Format : 767 x 493 <br/>
                File Size  : {{config('bueno.image.item_file_size_1').'kb - '.config('bueno.image.item_file_size_2').'kb'}}
                        <div class="pic">
                        </div>
                      <div class="control-group">
                            <label for="post_featured_image">
                                Choose a picture:
                            </label>
                            <input name="display_pic_url" type="file" accept="image/*" value="{{old('display_pic_url')}}">
                        </div>
                    </div>

                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">Thumbnail picture</label>
                <div class="col-sm-10 col-md-8">
                    <div class="well">
                    Format : 767 x 493 <br/>
                File Size  : {{config('bueno.image.item_file_size_1').'kb - '.config('bueno.image.item_file_size_2').'kb'}}
                        <div class="pic">
                        </div>
                        <div class="control-group">
                            <label>
                                Choose a picture:
                            </label>
                            <input name="thumbnail_pic_url" type="file" accept="image/*" value="{{old('thumbnail_pic_url')}}" >
                        </div>
                    </div>

                </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Meals in Combo</label>
              <div class="col-sm-10 col-md-8">
                  <div class="well">
                       <div class="control-group combo-meals">
                          <div class="combo-meals-options hidden">
                                @foreach($meals as $meal)
                                <option value="{{$meal->id}}">{{$meal->name}}</option>
                              @endforeach
                          </div>
                          <a href="#" class="btn btn-danger btn-xs" id="add-meal">add a meal</a>
                       </div>
                      </div>

                      <?php
                      $meal_count = 0;
                      ?>
                      @if(old('meals')!=null)
                      @foreach(old('meals') as $combo_meal)
                        <div class="input select add-new-combo-meal"><label >Meal</label>
                        <select name="meals[{{$meal_count}}][id]" class="meals_dp" required="">
                          @foreach($meals as $meal)
                                <option value="{{$meal->id}}" 
                                @if($meal->id==$combo_meal['id'])
                                selected="" 
                                @endif
                                >
                                {{$meal->name}}
                                </option>
                        @endforeach
                        </select>

                        <label for="OrderQuantity1">Quantity</label>
                        <input name="meals[{{$meal_count++}}][quantity]" class="qty_dp" value="{{$combo_meal['quantity']}}" type="number" min="0"  />
                        <a class="remove-meal">Remove</a>
                        <br>
                        </div>
                      @endforeach
                      @endif
                      <input type="hidden" class="meal-count" @if(old('meal_count')) value="{{old('meal_count')}}"@else value="0" @endif name="meal_count" required="">
                      <input type="hidden" name="actual_meal_count" class="actual-meal-count" @if(old('actual_meal_count')) value="{{old('actual_meal_count')}}" @else value="0" @endif>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Menu Display Order</label>
              <div class="col-sm-10 col-md-8">
                <input type="number" class="form-control" name="weight" min="1" required="" value="{{old('weight')}}" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Original Price</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" name="original_price" required="" value="{{old('original_price')}}" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Discounted Price</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" name="discount_price" value="{{old('discount_price')}}" />
              </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">Food Cost</label>
                <div class="col-sm-10 col-md-8">
                    <input type="text" class="form-control" name="food_cost" required="" value="{{old('food_cost')}}" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">Total Cost</label>
                <div class="col-sm-10 col-md-8">
                    <input type="text" class="form-control" name="total_cost" value="{{old('total_cost')}}" />
                </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Combo short description<br/>(<span id="short-length">0</span> char)</label>
              <div class="col-sm-10 col-md-8">
                <textarea name="description" class="form-control short-desc" >{{old('description')}}</textarea>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Combo long description<br/>(<span id="long-length">0</span> char)</label>
              <div class="col-sm-10 col-md-8">
                <textarea name="long_description" class="form-control long-desc">{{old('long_description')}}</textarea>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Combo Status</label>
              <div class="col-sm-10 col-md-8">
                <select class="form-control"  id="status" name="status">
                    @foreach(config('bueno.item_status') as $key => $value)
                        <option value="{{$key}}" @if(old('status')==$key)   selected @endif>{{$value}}</option>
                    @endforeach</select>
              </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 col-md-2 control-label">Recommended Items</label>
                <div class="col-sm-10 col-md-8">
                    <select class="form-control GroupGroup" multiple="multiple" name="items[]">
                        @foreach($items as $item)
                            <option value="{{$item->id}}" @if(old('items')!=null) @if(in_array($item->id,old('items'))) selected="" @endif @endif>{{$item->itemable->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10 col-md-offset-2 col-md-10">
                  <div class="checkbox">
                    <label>
                        <input class="hot-deal" type="checkbox" name="is_hot_deal" @if(old('is_hot_deal')==1) checked="" @endif value="1" /> Today's Special
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                        <input class="xprs-menu" type="checkbox" name="is_xprs" @if(old('is_xprs')==1) checked="" @endif value="1"/> Xprs Menu
                    </label>
                  </div>
                  <div class="checkbox">
                      <label>
                          <input class="is-recommended" type="checkbox" name="is_recommended" value="1" @if(old('is_xprs')==1) checked="" @endif/> Recommended
                      </label>
                  </div>
                  <div class="checkbox">
                    <label>
                        <input class="not-sale" type="checkbox" name="is_sellable" value="0" @if(old('is_sellable')==='0') checked @endif/> Not For Sale
                    </label>
                  </div>
              </div>
            </div>
				  	<div class="form-group form-actions">
				    	<div class="col-sm-offset-2 col-sm-10 col-md-offset-2 col-md-10">
				    		<a href="{{URL::route('admin.combos')}}" class="btn btn-default">Back</a>
				      		<button type="submit" class="btn btn-success" id="combo-button">Save</button>
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

      // Form validation
      $('#new-combo').validate({
        rules: {
         
        },
        highlight: function (element) {
          $(element).closest('.form-group').removeClass('success').addClass('error');
        },
        success: function (element) {
          element.addClass('valid').closest('.form-group').removeClass('error').addClass('success');
        }
      });

        $('.GroupGroup').select2();
    });
$('#availability').select2();

$('#add-meal').click(function(event) {

  
  event.preventDefault();
    options = $('.combo-meals-options').html();
    count = $('.meal-count').val();
    actual_count = $('.actual-meal-count').val();
    html = '  <div class="input select add-new-combo-meal"><label for="OrderMealId1">Meal</label>';
    html += '<select name="meals[' + count + '][id]" class="meals_dp" id="OrderMealId1">';
    html += options;
    html += '</select>';
    html += '<label for="OrderQuantity1">Quantity</label>';
    html += ' <input name="meals[' + count +'][quantity]" class="qty_dp" value="1" type="number" min="1" id="OrderQuantity1" />';
    html += '<a class="remove-meal">Remove</a>';
    html += '<br></div>';

    $('.meal-count').val(++count);
    $('.actual-meal-count').val(++actual_count);
    $('.combo-meals').append(html);
});

  $(document).on('click', '.remove-meal', function(event){
    $(this).parents('.add-new-combo-meal').remove();
    actual_count = $('.actual-meal-count').val();
    $('.actual-meal-count').val(--actual_count);

  });

  function convertToSlug(Text)
{
    return Text
        .toLowerCase()
        .replace(/ /g,'-')
        .replace(/[^\w-]+/g,'')
        ;
}

$('.item-name').keyup(function(event) {
  var text = $(this).val();
    text = text.replace(/\s+/g,' ').trim();
  var slug = convertToSlug(text);
  $('.slug').val(slug);
});

$('.item-name').focusout(function(event) {
        var text = $(this).val();
        text = text.replace(/\s+/g,' ').trim();
        var slug = convertToSlug(text);
        $('.slug').val(slug);
    });




$('#combo-button').on("click",function(event){
    event.preventDefault();
    if ($(".actual-meal-count").val() > 0) {
        $("#new-combo").submit();
    }
    else {
        Messenger().post({
              message: "Add At Least One Meal",
              type: "error",
              showCloseButton: true
          });
    }
    
});

    $('input.hot-deal').on('change', function() {
        if ($('input.hot-deal:checked').length>0) {
            if($('input.not-sale:checked').length>0)
            {
                Messenger().post({
                    message: "Meal Cannot be Today's Special and Not For Sale at same time",
                    type: 'error',
                    showCloseButton: true
                });
                $('input.not-sale').not(this).prop('checked', false);
            }
        }
    });

    $('input.xprs-menu').on('change', function() {
        if ($('input.xprs-menu:checked').length>0) {
            if($('input.not-sale:checked').length>0)
            {
                Messenger().post({
                    message: 'Meal Cannot be in Xprs Menu and Not For Sale at same time',
                    type: 'error',
                    showCloseButton: true
                });
                $('input.not-sale').not(this).prop('checked', false);
            }
        }
    });

    $('input.not-sale').on('change', function() {
        if ($('input.not-sale:checked').length>0) {

            if($('input.xprs-menu:checked').length>0 || $('input.hot-deal:checked').length>0)
            {
                Messenger().post({
                    message:"Meal Cannot be in Xprs Menu / Today's Special and Not For Sale at same time",
                    type: 'error',
                    showCloseButton: true
                });
                $('input.hot-deal').not(this).prop('checked', false);
                $('input.xprs-menu').not(this).prop('checked', false);
            }
        }
    });


      $('.short-desc').keyup(updateShortCount);
      $('.short-desc').keydown(updateShortCount);
      $('.long-desc').keyup(updateLongCount);
      $('.long-desc').keydown(updateLongCount);

function updateShortCount() {
    var cs = $(this).val().length;
    $('#short-length').text(cs);
}
function updateLongCount() {
    var cs = $(this).val().length;
    $('#long-length').text(cs);
}

  </script>

	@endsection