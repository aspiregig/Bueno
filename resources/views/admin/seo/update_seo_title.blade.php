
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
                   Update SEO title
                </div>
            </div>

            <div class="content-wrapper">
                @include('admin.partials.errors')
                @include('admin.partials.flash')
                <form id="new-customer" class="form-horizontal" method="post" role="form" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <input type="hidden" name="id" value="{{$web_page->id}}"> 
                <div class="form-group">
                    <label class="col-sm-2 col-md-2 control-label">Alias Name</label>
                    <div class="col-sm-10 col-md-8">
                        <div class="has-feedback">
                            <select name="slug" class="form-control" id="slug">
                            <option value="{{$web_page->slug}}"  selected="">{{$web_page->slug}}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Page Title</label>
                        <div class="col-sm-10 col-md-8">
                          <input type="text" class="form-control" name="meta_title" value="{{$web_page->meta_title}}" required=""/>
                        </div>
                </div>
                    
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Meta Description</label>
                        <div class="col-sm-10 col-md-8">
                          <textarea class="form-control" name="meta_description" required="">{{$web_page->meta_description}}</textarea> 
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">OG Image</label>
                        <div class="col-sm-10 col-md-8">
                          <div class="well">
                                <div class="pic">
                                    <img src="{{route('photo.web_page',$web_page->meta_image_url)}}" class="img-responsive" width="300px" />
                                </div>
                                
                                <div class="control-group">
                                    <label for="post_featured_image">
                                        Choose a picture:
                                    </label>
                                    <input id="post_featured_image" name="meta_image_url" type="file" accept="image/*">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-actions">
                        <div class="col-sm-offset-2 col-sm-10">
                            <a href="{{URL::route('admin.seo_titles',$web_page->id)}}" class="btn btn-default">Back</a>
                            <button  type="submit" class="btn btn-success">Update SEO Title</button>
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
            Do you want to delete this SEO details? All the info will be erased.
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a href="{{URL::route('admin.delete_seo_title',$web_page->id)}}"  class="btn btn-danger">Yes, delete it</a>
              </div>
          </form>
        </div>
      </div>
  </div>

  @endsection

  @section('script')

  <script type="text/javascript">
        $(function () {

            $('#slug').select2();

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