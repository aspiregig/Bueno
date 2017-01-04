
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
            <div class="menubar">
                <div class="sidebar-toggler visible-xs">
                    <i class="ion-navicon"></i>
                </div>

                <div class="page-title">
                   Update Slider
                </div>
            </div>

            <div class="content-wrapper">
                @include('admin.partials.errors')
                @include('admin.partials.flash')
                 <form id="new-customer" class="form-horizontal" method="post" role="form" enctype="multipart/form-data">
                    {{ csrf_field() }}
                <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Slider Name</label>
                        <div class="col-sm-10 col-md-8">
                          <input type="text" class="form-control" name="name" @if(old('name')!=null) value="{{old('name')}}" @else value="{{$slider->name}}" @endif/>
                        </div>
                </div>
                <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Link</label>
                        <div class="col-sm-10 col-md-8">
                          <input type="text" class="form-control" name="link_url" @if(old('link_url')!=null) value="{{old('link_url')}}" @else value="{{$slider->link_url}}" @endif required=""/>
                        </div>
                </div>
                    
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-8">
                          <textarea class="form-control" name="description" required="">@if(old('description')==null){{$slider->description}} @else{{old('description')}} @endif</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Slider Image</label>
                        <div class="col-sm-10 col-md-8">
                          <div class="well">
                          Format : 1919 x 697 <br/>
                File Size  : {{config('bueno.image.slider_file_size_1').'kb - '.config('bueno.image.slider_file_size_2').'kb'}}
                                <div class="control-group">
                                    <label for="post_featured_image">
                                        Update slider image:
                                    </label>
                                    <input name="image_url" type="file" accept="image/*">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Sequence</label>
                        <div class="col-sm-10 col-md-8">
                          <input type="text" class="form-control" name="sequence" required="" @if(old('sequence'))value="{{old('sequence')}}" @else value="{{$slider->sequence}}" @endif/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Status</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="has-feedback">
                                <select name="status" class="form-control" id="OrderLocality">
                                <option value="1" @if($slider->status) selected="" @endif>Active</option>
                                <option value="0" @if(!$slider->status) selected="" @endif>Disabled</option>
                                </select>
                            </div>
                        </div>
                    </div>
            </div>
                    <div class="form-group form-actions">
                        <div class="col-sm-offset-2 col-sm-10">
                            <a href="{{URL::route('admin.home_sliders')}}" class="btn btn-default">Cancel</a>
                            <button  type="submit" class="btn btn-success">Update Slider</button>
                            <a href="#" data-toggle="modal" data-target="#confirm-modal" class="btn btn-danger">Delete</a>
                        </div>
                    </div>
                </form>
                  
            </div>
        </div>

                    <!-- Confirm Modal -->
  <div class="modal fade" id="confirm-modal" tabindex="-1" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <form method="get" action="#" role="form">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">
                  Are you sure you want to delete this?
                </h4>
              </div>
              <div class="modal-body">
            Do you want to delete this Slider? All the info will be erased.
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a href="{{URL::route('admin.delete_home_slider',$slider->id)}}"  class="btn btn-danger">Yes, delete it</a>
              </div>
          </form>
        </div>
      </div>
  </div>



  @endsection

  @section('script')

  <script type="text/javascript">
        $(function () {

            // form validation
            $('#new-customer').validate({
                rules: {
                    "customer[first_name]": {
                        required: true
                    },
                    "customer[email]": {
                        required: true,
                        email: true
                    },
                    "customer[address]": {
                        required: true
                    },
                    "customer[notes]": {
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


           
        });
    </script>

  @endsection