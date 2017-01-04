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
          Add New Header Text
        </div>
      </div>

      <div class="content-wrapper">
        <form id="new-product" class="form-horizontal" method="post" action="#" role="form">
                    {{ csrf_field() }}
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Display picture</label>
              <div class="col-sm-10 col-md-8">
                <div class="well">
                  
                          
                          <div class="control-group">
                            <label for="post_featured_image">
                              Choose a picture:
                            </label>
                            <input id="post_featured_image" name="post[featured_image]" type="file">
                        </div>
                          <div class="control-group">
                                <label for="post_images_attributes_0_alt">Alt:</label>
                                <input class="form-control" name="alt_text" size="30" style="width: 50%;" type="text" />
                            </div>
                            <a href="#" class="remove-image">Remove image</a>
                    </div>
              </div>
            </div>
            
            <div class="form-group">
              <label for="inputPassword3" class="col-sm-2 col-md-2 control-label">Category</label>
              <div class="col-sm-10 col-md-8">
                <select class="form-control" data-smart-select name="category_id">
                @foreach($categories as $category)
                  <option value="{{$category->id}}">{{$category->name}}</option>
                @endforeach
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="inputPassword3" class="col-sm-2 col-md-2 control-label">Status</label>
              <div class="col-sm-10 col-md-8">
                <select class="form-control"  id="status" name="status">
                  <option value="1">Active</option>
                  <option value="0">Disabled</option>
                </select>
              </div>
            </div>
            <div class="form-group form-actions">
              <div class="col-sm-offset-2 col-sm-10 col-md-offset-2 col-md-10">
                <a href="{{URL::route('admin.banners')}}" class="btn btn-default">Cancel</a>
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

      // jQuery rating
      $('#raty').raty({
        path: 'images/raty',
        score: 4
      });

      // Datepicker
          $('.datepicker').datepicker({
            autoclose: true,
            orientation: 'left bottom',
          });

          // Minicolors colorpicker
          $('input.minicolors').minicolors({
            position: 'top left',
            defaultValue: '#9b86d1',
            theme: 'bootstrap'
          });

          // masked input example using phone input
      $(".mask-phone").mask("(999) 999-9999");
      $(".mask-cc").mask("9999 9999 9999 9999");
    });
  </script>

  @endsection