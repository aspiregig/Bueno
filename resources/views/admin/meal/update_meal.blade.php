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
  @include('admin.partials.errors')
  @include('admin.partials.flash')
      <div class="menubar">
        <div class="sidebar-toggler visible-xs">
          <i class="ion-navicon"></i>
        </div>

        <div class="page-title">
          Update Meal
        </div>
      </div>

      <div class="content-wrapper">
        <form id="new-product" class="form-horizontal" method="post" role="form" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <input type="hidden" name="id" value="{{$item->itemable->id}}"> 
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Meal Name</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" name="name" @if(old('name')!=null) value="{{old('name')}}" @else value="{{$item->itemable->name}}" @endif required=""/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Meal Alias</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" name="slug" value="{{$item->itemable->slug}}" required="" readonly=""/>
              </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">Product SKU</label>
                <div class="col-sm-10 col-md-8">
                    <input type="text" class="form-control" name="product_sku" @if(old('product_sku')!=null) value="{{old('product_sku')}}" @else value="{{$item->product_sku}}" @endif required=""/>
                </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Meal Serves</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" name="serves" @if(old('serves')!=null) value="{{old('serves')}}" @else value="{{$item->itemable->serves}}" @endif required=""/>
              </div>
            </div>
            <div class="form-group">
              <label  class="col-sm-2 col-md-2 control-label">Vegetarian/ Non-Vegetarian :</label>
              <div class="col-sm-10 col-md-8">
                <select class="form-control" data-smart-select name="type">
                  @foreach($meal_types as $meal_type)
                        <option value="{{$meal_type->id}}" @if(old('type')==$meal_type->id) selected="" @elseif(old('type') == null && $item->itemable->type == $meal_type->id) selected="" @endif>{{$meal_type->name}}</option>
                    @endforeach
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Cuisine</label>
              <div class="col-sm-10 col-md-8">
                 <select class="form-control" data-smart-select name="cuisine_id" required="">
                @foreach($cuisines as $cuisine)
                  <option value="{{$cuisine->id}}" @if(old('cuisine_id')==$cuisine->id) selected="" @elseif(old('cuisine_id') == null && $item->itemable->cuisine_id==$cuisine->id) selected="" @endif>{{$cuisine->name}}</option>
                @endforeach
                </select>
              </div>
            </div>
            <div class="form-group">
              <label  class="col-sm-2 col-md-2 control-label">Spice Level</label>
              <div class="col-sm-10 col-md-8">
                <select class="form-control" data-smart-select name="spice_level" required="">
                @foreach($spice_levels as $spice_level)
                  <option value="{{$spice_level->id}}" @if(old('spice_level') == $spice_level->id) selected="" @elseif(old('spice_level')==null && $item->itemable->spice_level == $spice_level->id) selected="" @endif>{{$spice_level->name}}</option>
                @endforeach
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Category</label>
              <div class="col-sm-10 col-md-8">
                <select class="form-control" data-smart-select name="category_id" required="">
                @foreach($categories as $category)
                  <option value="{{$category->id}}" @if(old('category_id')==$category->id) selected="" @elseif( old('category_id') == null && 
                  $item->itemable->category_id==$category->id) selected="" @endif>{{$category->name}}</option>
                @endforeach
                </select>
              </div>
            </div>
            <div class="form-group">
              <label  class="col-sm-2 col-md-2 control-label">Availability</label>
              <div class="col-sm-10 col-md-8">
                <select class="form-control"  id="availability" multiple="multiple" name="availabilities[]" required="">
                    @foreach($availabilities as $availability)
                        <option value="{{$availability->id}}" @if(old('availabilities')) @if(in_array($availability->id,old('availabilities'))) selected @endif @elseif( old('availabilities') == null && in_array($availability->id,$item->availabilities->pluck('id')->toArray())) selected="" @endif >{{$availability->name}}</option>
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
                    <img src="{{ $item->image_url }}" class="img-responsive" />
                  </div>
                          
                          <div class="control-group">
                            <label for="post_featured_image">
                              Choose a picture:
                            </label>
                            <input name="display_pic_url" type="file">
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
                            <img src="{{ $item->thumb_image_url }}" class="img-responsive" />
                        </div>
                        <div class="control-group">
                            <label>
                                Choose a picture:
                            </label>
                            <input name="thumbnail_pic_url" type="file">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Menu Order</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" name="weight" @if(old('weight')!=null) value="{{old('weight')}}" @else value="{{$item->itemable->weight}}" @endif required=""/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Original Price</label>
              <div class="col-sm-10 col-md-8">
                <input type="number" class="form-control" name="original_price" @if(old('original_price')) value="{{old('original_price')}}" @else value="{{$item->itemable->original_price}}" @endif required=""/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Discounted Price</label>
              <div class="col-sm-10 col-md-8">
                <input type="number" class="form-control" name="discount_price" @if(old('discount_price')) value="{{old('discount_price')}}" @else value="{{$item->itemable->discount_price}}" @endif required="" />
              </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">Food Cost</label>
                <div class="col-sm-10 col-md-8">
                    <input type="number" class="form-control" name="food_cost" @if(old('food_cost')) value="{{old('food_cost')}}" @else value="{{$item->food_cost}}" @endif required=""/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">Total Cost</label>
                <div class="col-sm-10 col-md-8">
                    <input type="number" class="form-control" name="total_cost" @if(old('total_cost')) value="{{old('total_cost')}}" @else value="{{$item->total_cost}}" @endif required="" />
                </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Meal short description<br/>(<span id="short-length">{{strlen($item->itemable->description)}}<span> char)</label></label>
              <div class="col-sm-10 col-md-8">
                <textarea  name="description" class="form-control short-desc">@if(old('description')){{old('description')}}@else{{$item->itemable->description}}@endif</textarea>
              </div>
            </div><div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Meal long description<br/>(<span id="long-length">{{strlen($item->itemable->long_description)}}<span> char)</label>
              <div class="col-sm-10 col-md-8">
                <textarea  name="long_description" class="form-control long-desc">@if(old('long_description')){{old('long_description')}}@else{{$item->itemable->long_description}}@endif</textarea>
              </div>
            </div>
            <div class="form-group">
              <label  class="col-sm-2 col-md-2 control-label">Meal Status </label>
              <div class="col-sm-10 col-md-8">
                <select class="form-control GroupGroup"  name="status" >
                    @foreach(config('bueno.item_status') as $key => $value)
                  <option value="{{$key}}" @if(old('status')==$key)   selected @elseif (old('status')==null && $item->itemable->status==$key)  selected="" @endif>{{$value}}</option>
                    @endforeach
                </select>
              </div>
            </div>
            <div class="form-group">
              <label  class="col-sm-2 col-md-2 control-label">Recommended Items </label>
              <div class="col-sm-10 col-md-8">
                <select class="form-control"  id="availability" multiple="multiple" name="items[]">
                   @foreach($items as $ritem)
                  @if(!($ritem->id == $item->id))
                  <option value="{{$ritem->id}}" @if(old('items')) @if(in_array($ritem->id,old('items'))) selected @endif @elseif(old('items') == null && in_array($ritem->id,$item->recommendedItems->pluck('id')->toArray())) selected="" @endif>{{$ritem->itemable->name}}</option>
                  @endif
                 @endforeach
                </select>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10 col-md-offset-2 col-md-10">
                  <div class="checkbox">
                    <label>
                        <input class="hot-deal" type="checkbox" name="is_hot_deal" value="1" @if(old('is_hot_deal')==1) checked="" @elseif($item->itemable->is_hot_deal) checked="" @endif/> Today's Special
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                        <input class="xprs-menu" type="checkbox" name="is_xprs" value="1" @if(old('is_xprs')==1) checked="" @elseif($item->itemable->is_xprs) checked="" @endif/> Xprs Menu
                    </label>
                  </div>
                  <div class="checkbox">
                      <label>
                          <input class="is-recommended" type="checkbox" name="is_recommended" value="1" @if(old('is_recommended')==1) checked="" @elseif($item->is_recommended==1) checked="" @endif/> Recommended
                      </label>
                  </div>
                  <div class="checkbox">
                    <label>
                        <input class="not-sale" type="checkbox" name="is_sellable" value="0" @if(old('is_sellable')===0) checked="" @elseif($item->itemable->is_sellable==0) checked="" @endif/> Not For Sale
                    </label>
                  </div>
              </div>
            </div>
            <div class="form-group form-actions">
              <div class="col-sm-offset-2 col-sm-10 col-md-offset-2 col-md-10">
                <a href="{{URL::route('admin.meals')}}" class="btn btn-default">Cancel</a>
                  <button type="submit" class="btn btn-success">Save Meal</button>
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
                  Are you sure you want to delete this Meal?
                </h4>
              </div>
              <div class="modal-body">
            Do you want to delete this Meal? All the info will be erased.
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a href="{{URL::route('admin.delete_meal',$item->id)}}"  class="btn btn-danger">Yes, delete it</a>
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

      $('input.not-sale').on('change', function() {
        if ($('input.not-sale:checked').length>0) {
          
          if($('input.xprs-menu:checked').length>0 || $('input.hot-deal:checked').length>0)
          {
            Messenger().post({
                  message: "Meal Cannot be in Xprs Menu / Today's Special and Not For Sale at same time",
                  type: 'error',
                  showCloseButton: true
              });
          $('input.hot-deal').not(this).prop('checked', false);
          $('input.xprs-menu').not(this).prop('checked', false);
          }
        }
        });

$('.GroupGroup').select2();

          // masked input example using phone input
      $(".remove-image").click(function(e){
        e.preventDefault();
        $(".img-responsive").attr("src","");
      });
    });
$('#availability').select2();
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