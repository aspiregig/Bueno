
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
                    Add a new Ad Text
                </div>
            </div>

            <div class="content-wrapper" id="form-product">
                @include('admin.partials.errors')
                @include('admin.partials.flash')
                <form id="new-ad" class="form-horizontal" method="post" role="form" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-8">
                          <input type="text" class="form-control" name="name" required=""/>
                        </div>
                    </div>
                  <div class="form-group">
                    <label class="col-sm-2 col-md-2 control-label">Html content</label>
                    <div class="col-sm-10 col-md-8">
                      <textarea name="html_content" class="form-control html-content" required=""></textarea>
                    </div>
                  </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Status</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="has-feedback">
                                <select name="status" class="form-control" id="OrderLocality">
                                <option value="0">Disable</option>
                                <option value="1">Active</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group form-actions">
                        <div class="col-sm-offset-2 col-sm-10">
                            <a href="{{URL::route('admin.ad_texts')}}" class="btn btn-default">Back</a>
                            <button type="submit" class="btn btn-success">Add Ad</button>
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
            $('#new-ad').validate({
                rules: {
                    "name": {
                        required: true
                    },
                    "html_content": {
                        required: true,
                    }
                },
                highlight: function (element) {
                    $(element).closest('.form-group').removeClass('success').addClass('error');
                },
                success: function (element) {
                    element.addClass('valid').closest('.form-group').removeClass('error').addClass('success');
                }
            });
            // Bootstrap wysiwyg
      $(".html-content").summernote({
        height: 240,
        toolbar: [
            
            ['view', ['fullscreen','codeview']],
        ]
      });



           
        });
    </script>

  @endsection