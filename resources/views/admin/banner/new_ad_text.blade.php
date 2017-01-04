
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
                    Add a new Banner
                </div>
            </div>

            <div class="content-wrapper">
                @include('admin.partials.errors')
                @include('admin.partials.flash')
                <form id="new-customer" class="form-horizontal" method="post" role="form" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Banner</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="well">
                            Format : 1027 x 272 <br/>
                File Size  : {{config('bueno.image.banner_file_size_1').'kb - '.config('bueno.image.banner_file_size_1').'kb'}}
                                <div class="pic">
                                </div>
                              <div class="control-group">
                                    <label for="post_featured_image">
                                        Choose a picture:
                                    </label>
                                    <input id="post_featured_image" name="display_pic_url" type="file" accept="image/*" required="" value="{{old('display_pic_url')}}">
                                </div>
                            </div>
                    
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Alt Text</label>
                        <div class="col-sm-10 col-md-8">
                            <textarea class="form-control" name="content" required="">{{old('content')}}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Link url</label>
                        <div class="col-sm-10 col-md-8">
                          <input class="form-control" name="link_url" type="text" required="" value="{{old('link_url')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Status</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="has-feedback">
                                <select name="status" class="form-control" >
                                <option value="0" @if(old('status')==0) selected @endif>Disable</option>
                                <option value="1" @if(old('status')==1) selected @endif>Active</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group form-actions">
                        <div class="col-sm-offset-2 col-sm-10">
                            <a href="{{URL::route('admin.html_banners')}}" class="btn btn-default">Back</a>
                            <button type="submit" class="btn btn-success">Add Banner</button>
                        </div>
                    </div>
                </form>
                  
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