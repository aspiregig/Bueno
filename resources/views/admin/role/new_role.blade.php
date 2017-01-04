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
          Add a new Role
        </div>
      </div>

      <div class="content-wrapper">
        <form id="new-product" class="form-horizontal" method="post" action="#" role="form">
                    {{ csrf_field() }}
            <div class="form-group">
              <label for="inputPassword3" class="col-sm-2 col-md-2 control-label">Applied to Group</label>
              <div class="col-sm-10 col-md-8">
                <select class="form-control" data-smart-select>
                  <option>Admins</option>
                  <option>Managers</option>
                  <option>Users</option>
                </select>
              </div>
            </div>     
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Users</label>
              <div class="col-sm-10 col-md-8">
                <input type="checkbox" name=""   /> Manage Permission</div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Orders</label>
              <div class="col-sm-10 col-md-8">
                <input type="checkbox" name=""  /> Manage Permission</div>
            </div><div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Meals</label>
              <div class="col-sm-10 col-md-8">
                <input type="checkbox" name=""  /> Manage Permission</div>
            </div><div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Coupons</label>
              <div class="col-sm-10 col-md-8">
                <input type="checkbox" name=""  /> Manage Permission</div>
            </div><div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Cities</label>
              <div class="col-sm-10 col-md-8">
                <input type="checkbox" name=""  /> Manage Permission</div>
            </div><div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Header Ads Texts</label>
              <div class="col-sm-10 col-md-8">
                <input type="checkbox" name=""  /> Manage Permission</div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Testimonials</label>
              <div class="col-sm-10 col-md-8">
                <input type="checkbox" name="manage_testimonial" value="1"  /> Manage Permission</div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Ratings & Reviews</label>
              <div class="col-sm-10 col-md-8">
                <input type="checkbox" name=""  /> Manage Permission</div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">SEO Titles</label>
              <div class="col-sm-10 col-md-8">
                <input type="checkbox" name=""  /> Manage Permission</div>
            </div><div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Category Banners</label>
              <div class="col-sm-10 col-md-8">
                <input type="checkbox" name=""  /> Manage Permission</div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Settings</label>
              <div class="col-sm-10 col-md-8">
                <input type="checkbox" name=""  /> Manage Permission</div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Rules</label>
              <div class="col-sm-10 col-md-8">
                <input type="checkbox" name=""  /> Manage Permission</div>
            </div><div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Groups</label>
              <div class="col-sm-10 col-md-8">
                <input type="checkbox" name=""  /> Manage Permission</div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Kitchens</label>
              <div class="col-sm-10 col-md-8">
                <input type="checkbox" name=""  /> Manage Permission</div>
            </div>
            <div class="form-group form-actions">
              <div class="col-sm-offset-2 col-sm-10 col-md-offset-2 col-md-10">
                <a href="form.html" class="btn btn-default">Cancel</a>
                  <button type="submit" class="btn btn-success">Update Role </button>
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