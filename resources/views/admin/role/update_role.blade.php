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
          Update Role
        </div>
      </div>

      <div class="content-wrapper">
          @include('admin.partials.flash')
          @include('admin.partials.errors')
        <form id="new-product" class="form-horizontal" method="post" action="#" role="form">
                    {{ csrf_field() }}
            <div class="form-group">
              <label for="inputPassword3" class="col-sm-2 col-md-2 control-label">Applied to Group</label>
              <div class="col-sm-10 col-md-8">
                <select class="form-control" data-smart-select>
                  <option value="{{$group->id}}">{{$group->name}}</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="dashboard" class="col-sm-2 col-md-2 control-label">Dashboard</label>
              <div class="col-sm-10 col-md-8">
                <input id="dashboard" type="checkbox" value="1" name="permissions[dashboard]" @if(in_array('dashboard', $permissions)) checked="" @endif /> Manage Permission</div>
            </div>
            <div class="form-group">
              <label for="users" class="col-sm-2 col-md-2 control-label">Users</label>
              <div class="col-sm-10 col-md-8">
                <input id="users" type="checkbox" value="1" name="permissions[users]" @if(in_array('users', $permissions)) checked="" @endif /> Manage Permission</div>
            </div>
            <div class="form-group">
              <label for="orders" class="col-sm-2 col-md-2 control-label">Orders</label>
              <div class="col-sm-10 col-md-8">
                <input id="orders" type="checkbox" value="1" name="permissions[orders]" @if(in_array('orders', $permissions)) checked="" @endif/> Manage Permission</div>
            </div>
            <div class="form-group">
              <label for="reports" class="col-sm-2 col-md-2 control-label">Reports</label>
              <div class="col-sm-10 col-md-8">
                <input id="reports" type="checkbox" value="1" name="permissions[reports]" @if(in_array('reports', $permissions)) checked="" @endif/> Manage Permission</div>
            </div>
            <div class="form-group">
              <label for="meals" class="col-sm-2 col-md-2 control-label">Meals and Combos</label>
              <div class="col-sm-10 col-md-8">
                <input id="meals" type="checkbox" value="1" name="permissions[meals]" @if(in_array('meals', $permissions)) checked="" @endif/> Manage Permission</div>
            </div>
            <div class="form-group">
              <label for="coupons" class="col-sm-2 col-md-2 control-label">Coupons</label>
              <div class="col-sm-10 col-md-8">
                <input id="coupons" type="checkbox" value="1" name="permissions[coupons]" @if(in_array('coupons', $permissions)) checked="" @endif/> Manage Permission</div>
            </div>
            <div class="form-group">
              <label for="locations" class="col-sm-2 col-md-2 control-label">Locations</label>
              <div class="col-sm-10 col-md-8">
                <input id="locations" type="checkbox" value="1" name="permissions[locations]" @if(in_array('locations', $permissions)) checked="" @endif/> Manage Permission</div>
            </div>
            <div class="form-group">
              <label for="banners" class="col-sm-2 col-md-2 control-label">Banners</label>
              <div class="col-sm-10 col-md-8">
                <input id="banners" type="checkbox" value="1" name="permissions[banners]" @if(in_array('banners', $permissions)) checked="" @endif/> Manage Permission</div>
            </div>
            <div class="form-group">
              <label for="headers" class="col-sm-2 col-md-2 control-label">Header Ads Texts</label>
              <div class="col-sm-10 col-md-8">
                <input id="headers"type="checkbox" value="1" name="permissions[ad_texts]" @if(in_array('ad_texts', $permissions)) checked="" @endif/> Manage Permission</div>
            </div>
            <div class="form-group">
              <label for="testimonials" class="col-sm-2 col-md-2 control-label">Testimonials</label>
              <div class="col-sm-10 col-md-8">
                <input id="testimonials" type="checkbox" value="1" name="permissions[testimonials]" @if(in_array('testimonials', $permissions)) checked="" @endif/> Manage Permission</div>
            </div>
            <div class="form-group">
              <label for="pages" class="col-sm-2 col-md-2 control-label">SEO Titles</label>
              <div class="col-sm-10 col-md-8">
                <input id="pages" type="checkbox" value="1" name="permissions[pages]" @if(in_array('pages', $permissions)) checked="" @endif/> Manage Permission</div>
            </div>
            <div class="form-group">
              <label for="settings" class="col-sm-2 col-md-2 control-label">Settings</label>
              <div class="col-sm-10 col-md-8">
                <input id="settings" type="checkbox" value="1" name="permissions[settings]" @if(in_array('settings', $permissions)) checked="" @endif/> Manage Permission</div>
            </div>
            <div class="form-group">
              <label for="roles" class="col-sm-2 col-md-2 control-label">Roles</label>
              <div class="col-sm-10 col-md-8">
                <input id="roles" type="checkbox" value="1" name="permissions[roles]" @if(in_array('roles', $permissions)) checked="" @endif/> Manage Permission</div>
            </div><div class="form-group">
              <label for="groups" class="col-sm-2 col-md-2 control-label">Groups</label>
              <div class="col-sm-10 col-md-8">
                <input id="groups" type="checkbox" value="1" name="permissions[groups]" @if(in_array('groups', $permissions)) checked="" @endif/> Manage Permission</div>
            </div>
            <div class="form-group">
              <label for="kitchens" class="col-sm-2 col-md-2 control-label">Kitchens</label>
              <div class="col-sm-10 col-md-8">
                <input id="kitchens" type="checkbox" value="1" name="permissions[kitchens]" @if(in_array('kitchens', $permissions)) checked="" @endif/> Manage Permission</div>
            </div>
            <div class="form-group">
              <label for="sliders" class="col-sm-2 col-md-2 control-label">Home Sliders</label>
              <div class="col-sm-10 col-md-8">
                <input id="sliders" type="checkbox" value="1" name="permissions[sliders]" @if(in_array('sliders', $permissions)) checked="" @endif/> Manage Permission</div>
            </div>
            <div class="form-group">
              <label for="business" class="col-sm-2 col-md-2 control-label">Business Queries</label>
              <div class="col-sm-10 col-md-8">
                <input id="business" type="checkbox" value="1" name="permissions[careers]" @if(in_array('careers', $permissions)) checked="" @endif/> Manage Permission</div>
            </div>
            <div class="form-group">
              <label for="catering" class="col-sm-2 col-md-2 control-label">Catering</label>
              <div class="col-sm-10 col-md-8">
                <input id="catering" type="checkbox" value="1" name="permissions[catering]" @if(in_array('catering', $permissions)) checked="" @endif/> Manage Permission</div>
            </div>
            <div class="form-group">
              <label for="general" class="col-sm-2 col-md-2 control-label">General Queries</label>
              <div class="col-sm-10 col-md-8">
                <input id="general" type="checkbox" value="1" name="permissions[queries]" @if(in_array('queries', $permissions)) checked="" @endif/> Manage Permission</div>
            </div>
            <div class="form-group">
              <label for="delivery-boys" class="col-sm-2 col-md-2 control-label">Delivery Boys</label>
              <div class="col-sm-10 col-md-8">
                <input id="delivery-boys" type="checkbox" value="1" name="permissions[delivery_boys]" @if(in_array('delivery_boys', $permissions)) checked="" @endif/> Manage Permission</div>
            </div>
            <div class="form-group">
              <label for="stocks" class="col-sm-2 col-md-2 control-label">Stocks</label>
              <div class="col-sm-10 col-md-8">
                <input id="stocks" type="checkbox" value="1" name="permissions[stock]" @if(in_array('stock', $permissions)) checked="" @endif/> Manage Permission</div>
            </div>
            <div class="form-group">
              <label for="ngos" class="col-sm-2 col-md-2 control-label">Ngos</label>
              <div class="col-sm-10 col-md-8">
                <input id="ngos" type="checkbox" value="1" name="permissions[ngos]" @if(in_array('ngos', $permissions)) checked="" @endif/> Manage Permission</div>
            </div>
            <div class="form-group">
                <label for="memberships" class="col-sm-2 col-md-2 control-label">Membership</label>
                <div class="col-sm-10 col-md-8">
                    <input id="memberships" type="checkbox" value="1" name="permissions[membership]" @if(in_array('membership', $permissions)) checked="" @endif/> Manage Permission</div>
            </div>
            <div class="form-group form-actions">
              <div class="col-sm-offset-2 col-sm-10 col-md-offset-2 col-md-10">
                <a href="{{URL::route('admin.roles')}}" class="btn btn-default">Cancel</a>
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