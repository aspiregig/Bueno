
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
                    Add a new SEO title
                </div>
            </div>

            <div class="content-wrapper">
                @include('admin.partials.errors')
                @include('admin.partials.flash')
                <form id="new-customer" class="form-horizontal" method="post" role="form" enctype="multipart/form-data">
                    {{ csrf_field() }}
                <div class="form-group">
                    <label class="col-sm-2 col-md-2 control-label">Alias Name</label>
                    <div class="col-sm-10 col-md-8">
                        <div class="has-feedback">
                            <select name="slug" class="form-control" id="slug">
                            <option value="home" @if(old('slug')=="home") selected="" @endif>Home</option>
                            <option value="career" @if(old('slug')=="career") selected="" @endif>Career</option>
                            <option value="catering" @if(old('slug')=="catering") selected="" @endif>Catering</option>
                            <option value="query" @if(old('slug')=="query") selected="" @endif>Query</option>
                            <option value="about" @if(old('slug')=="about") selected="" @endif>About us</option>
                            <option value="faq" @if(old('slug')=="faq") selected="" @endif>FAQ</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Page Title</label>
                        <div class="col-sm-10 col-md-8">
                          <input type="text" class="form-control" name="meta_title" required="" value="{{old('meta_title')}}" />
                        </div>
                </div>
                    
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Meta Description</label>
                        <div class="col-sm-10 col-md-8">
                          <textarea class="form-control" name="meta_description" required="" >{{old('meta_description')}}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">OG Image</label>
                        <div class="col-sm-10 col-md-8">
                          <div class="well">
                                
                                
                                <div class="control-group">
                                    <label for="post_featured_image">
                                        Choose a picture:
                                    </label>
                                    <input id="post_featured_image" name="meta_image_url" type="file" accept="image/*" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-actions">
                        <div class="col-sm-offset-2 col-sm-10">
                            <a href="{{URL::route('admin.seo_titles')}}" class="btn btn-default">Back</a>
                            <button  type="submit" class="btn btn-success">Add SEO Title</button>
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

            $('#slug').select2();
           
        });
    </script>

  @endsection