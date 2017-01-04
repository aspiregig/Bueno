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
					Add a New Meal
				</div>
			</div>

			<div class="content-wrapper">
        @include('admin.partials.errors')
        @include('admin.partials.flash')
				<form id="new-meal" class="form-horizontal" method="post" role="form" enctype="multipart/form-data">
                    {{ csrf_field() }}
            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">Meal Name</label>
                <div class="col-sm-10 col-md-8">
                  <input type="text" class="form-control item-name" name="name" required="" value="{{old('name')}}"/>
                </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Meal Alias</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control slug" name="slug" required="" @if(old('slug')) value="{{old('slug')}}" @endif readonly="" />
              </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">Product SKU</label>
                <div class="col-sm-10 col-md-8">
                    <input type="text" class="form-control" name="product_sku" required="" value="{{old('product_sku')}}"/>
                </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Meal Serves</label>
              <div class="col-sm-10 col-md-8">
                <input type="number" class="form-control" name="serves" min="1" required="" value="{{old('serves')}}"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Meal Type :</label>
              <div class="col-sm-10 col-md-8">
                <select class="form-control GroupGroup" name="type" required="">
                    @foreach($meal_types as $meal_type)
                        <option value="{{$meal_type->id}}" @if(old('type')==$meal_type->id) selected="" @endif>{{$meal_type->name}}</option>
                    @endforeach
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="inputPassword3" class="col-sm-2 col-md-2 control-label">Cuisine</label>
              <div class="col-sm-10 col-md-8">
                <select class="form-control GroupGroup" name="cuisine_id" required="">
                @foreach($cuisines as $cuisine)
                  <option value="{{$cuisine->id}}" @if(old('cuisine_id')==$cuisine->id) selected="" @endif>{{$cuisine->name}}</option>
                @endforeach
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="inputPassword3" class="col-sm-2 col-md-2 control-label">Spice Level</label>
              <div class="col-sm-10 col-md-8">
                <select class="form-control GroupGroup" name="spice_level" required="">
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
                <input type="hidden" class="form-control" name="stock" min="0" required="" value="0"/>
				  	<div class="form-group">
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
				                    <input name="display_pic_url" type="file" accept="image/*">
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
                                    <label for="post_featured_image">
                                        Choose a picture:
                                    </label>
                                    <input name="thumbnail_pic_url" type="file" accept="image/*">
                                </div>
                            </div>

                        </div>
                    </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Menu Display Order</label>
              <div class="col-sm-10 col-md-8">
                <input type="number" class="form-control" name="weight" min="1" required="" value="{{old('weight')}}"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Original Price</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" name="original_price" required="" value="{{old('original_price')}}"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Discounted Price</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" name="discount_price"  value="{{old('discount_price')}}" required=""/>
              </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">Food Cost</label>
                <div class="col-sm-10 col-md-8">
                    <input type="text" class="form-control" name="food_cost" required="" value="{{old('food_cost')}}"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">Total Cost</label>
                <div class="col-sm-10 col-md-8">
                    <input type="text" class="form-control" name="total_cost"  value="{{old('total_cost')}}" required=""/>
                </div>
            </div>
				  	<div class="form-group">
				  		<label class="col-sm-2 col-md-2 control-label">Meal short description<br/>(<span id="short-length">0</span>  char)</label>
				  		<div class="col-sm-10 col-md-8">
				  			<textarea name="description" class="form-control short-desc" >{{old('description')}}</textarea>
				  		</div>
				  	</div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Meal long description<br/>(<span id="long-length">0</span> char)</label>
              <div class="col-sm-10 col-md-8">
                <textarea name="long_description" class="form-control long-desc">{{old('long_description')}}</textarea>
              </div>
            </div>
            <div class="form-group">
              <label for="inputPassword3" class="col-sm-2 col-md-2 control-label">Meal Status</label>
              <div class="col-sm-10 col-md-8">
                <select class="form-control"  id="status" name="status">
                    @foreach(config('bueno.item_status') as $key => $value)
                        <option value="{{$key}}" @if(old('status')===$key)   selected @endif>{{$value}}</option>
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
                        <input class="hot-deal" type="checkbox" name="is_hot_deal" value="1" @if(old('is_hot_deal')==1) checked @endif/> Today's Special
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                        <input class="xprs-menu" type="checkbox" name="is_xprs" value="1" @if(old('is_xprs')==1) checked @endif/> Xprs Menu
                    </label>
                  </div>
                  <div class="checkbox">
                      <label>
                          <input class="is-recommended" type="checkbox" name="is_recommended" value="1" @if(old('is_recommended')==1) checked @endif/> Recommended
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
				    		<a href="{{URL::route('admin.meals')}}" class="btn btn-default">Cancel</a>
				      		<button type="submit" class="btn btn-success">Save</button>
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

        $('input.is-recommended').on('change', function() {
            if ($('input.is-recommended:checked').length>0) {
                if($('input.not-sale:checked').length>0)
                {
                    Messenger().post({
                        message: 'Meal Cannot be Recommended and Not For Sale at same time',
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
          $('input.is-recommended').not(this).prop('checked', false);
          }
        }
        });
     

    });
function convertToSlug(Text)
{
    return Text
        .toLowerCase()
        .replace(/ /g,'-')
        .replace(/[^\w-]+/g,'')
        ;
}
$('.GroupGroup').select2();

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
// Form validation
      $('#new-meal').validate({
        rules: {
         
        },
        highlight: function (element) {
          $(element).closest('.form-group').removeClass('success').addClass('error');
        },
        success: function (element) {
          element.addClass('valid').closest('.form-group').removeClass('error').addClass('success');
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