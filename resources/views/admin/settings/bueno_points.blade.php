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

        <div class="page-title">Referral Settings
        </div>
        
      </div>
      <div class="content-wrapper">
      @include('admin.partials.errors')
      @include('admin.partials.flash')
        <form id="new-product" class="form-horizontal" method="post" role="form">
                    {{ csrf_field() }}
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Referral Credits</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" name="referral_points" @if(old('referral_points')!=null) value="{{old('referral_points')}}" @else value="{{$settings['referral_points']}}" @endif/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Referrer Credits</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" name="referrer_points" @if(old('referrer_points')!=null) value="{{old('referrer_points')}}" @else value="{{$settings['referrer_points']}}" @endif/>
              </div>
            </div>
            
            <div class="form-group form-actions">
              <div class="col-sm-offset-2 col-sm-10 col-md-offset-2 col-md-10">
                <a href="{{URL::route('admin.dashboard')}}" class="btn btn-default">Back</a>
                  <button class="btn btn-success">Update </button>
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

      $("[name='my-checkbox']").bootstrapSwitch();

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

      $("[name='my-checkbox']").bootstrapSwitch();

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
