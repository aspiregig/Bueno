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
          Update a New Goody
        </div>
      </div>

      <div class="content-wrapper">
        <form id="new-product" class="form-horizontal" method="post" role="form" enctype="multipart/form-data">
                    {{ csrf_field() }}
            {{ method_field('PATCH') }}
            <input type="hidden" name="id" value="{{$goody->id}}">
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Goody Name</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" value="{{$goody->name}}" name="name" required=""/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Goody Worth</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" value="{{$goody->worth}}" name="worth" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Goody Quantity</label>
              <div class="col-sm-10 col-md-8">
                <input type="number" class="form-control" name="quantity" value="{{$goody->quantity}}" required="" />
              </div>
            </div>
            
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Display picture</label>
              <div class="col-sm-10 col-md-8">
                <div class="well">
                  <div class="pic">
                    <img src="{{route('photo.goody',$goody->image_url)}}" class="img-responsive" width="300px" />
                  </div>
                          
                          <div class="control-group">
                            <label for="post_featured_image">
                              Choose a picture:
                            </label>
                            <input id="post_featured_image" name="image_url" type="file">
                        </div>
                
                           
                    </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Goody description</label>
              <div class="col-sm-10 col-md-8">
                <textarea name="description" class="form-control" required="">{{$goody->description}}</textarea> 
              </div>
            </div>

            <div class="form-group form-actions">
              <div class="col-sm-offset-2 col-sm-10 col-md-offset-2 col-md-10">
                <a href="{{URL::route('admin.goodies')}}" class="btn btn-default">Cancel</a>
                  <button class="btn btn-success">Update Goody</button>
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