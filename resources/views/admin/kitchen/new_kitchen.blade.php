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
          Add New Kitchen 
        </div>
      </div>

      <div class="content-wrapper">
          @include('admin.partials.errors')
          @include('admin.partials.flash')
          <form id="new-product" class="form-horizontal" method="post" role="form">
        {{csrf_field()}}
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Kitchen Name</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" name="name"  required="" value="{{old('name')}}" />
              </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">Location Covered</label>
                <div class="col-sm-10 col-md-8">
                    <div class="has-feedback">
                        <select name="areas[]" multiple="multiple" class="GroupGroup form-control user-address-search" required="">
                        @foreach($areas as $area)
                              <option value="{{$area->id}}" @if(old('areas')) @if(in_array($area->id,old('areas'))) selected @endif @endif>{{$area->name}}, {{$area->city->name}} {{$area->city->state->name}}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Managers of this Kitchen</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="has-feedback">
                                <select name="managers[]" multiple="multiple" class="GroupGroup form-control" required="">
                                @foreach($users as $user)
                                <option value="{{$user->id}}" @if(old('managers')) @if(in_array($user->id,old('managers'))) selected @endif @endif>{{$user->full_name}}:{{$user->email}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                </div>
              <div class="form-group">
                  <label class="col-sm-2 col-md-2 control-label">Delivery Charge(INR)</label>
                  <div class="col-sm-10 col-md-8">
                      <input type="text" class="form-control" name="delivery_charge"  required="" value="{{old('delivery_charge')}}" />
                  </div>
              </div>
              <div class="form-group">
                  <label class="col-sm-2 col-md-2 control-label">Packaging Charge(INR)</label>
                  <div class="col-sm-10 col-md-8">
                      <input type="text" class="form-control" name="packaging_charge"  required="" value="{{old('packaging_charge')}}" />
                  </div>
              </div>
              <div class="form-group">
                  <label class="col-sm-2 col-md-2 control-label">VAT (%)</label>
                  <div class="col-sm-10 col-md-8">
                      <input type="text" class="form-control" name="vat"  required="" value="{{old('vat')}}" />
                  </div>
              </div>
              <div class="form-group">
                  <label class="col-sm-2 col-md-2 control-label">Service Tax</label>
                  <div class="col-sm-10 col-md-8">
                      <input type="text" class="form-control" name="service_tax"  required="" value="{{old('service_tax')}}" />
                  </div>
              </div>
              <div class="form-group">
                  <label class="col-sm-2 col-md-2 control-label">Service Charge</label>
                  <div class="col-sm-10 col-md-8">
                      <input type="text" class="form-control" name="service_charge"  required="" value="{{old('service_charge')}}" />
                  </div>
              </div>
              <div class="form-group">
                  <label class="col-sm-2 col-md-2 control-label">Jooleh Username</label>
                  <div class="col-sm-10 col-md-8">
                      <input type="text" class="form-control" name="jooleh_username"  required="" value="{{old('jooleh_username')}}" />
                  </div>
              </div>
              <div class="form-group">
                  <label class="col-sm-2 col-md-2 control-label">Jooleh Token</label>
                  <div class="col-sm-10 col-md-8">
                      <input type="text" class="form-control" name="jooleh_token"  required="" value="{{old('jooleh_token')}}" />
                  </div>
              </div>
            <div class="form-group">
              <label for="inputPassword3" class="col-sm-2 col-md-2 control-label">Kitchen Status</label>
              <div class="col-sm-10 col-md-8">
                <select class="form-control"  id="status" name="status">
                  <option value="1" @if(old('status')==1) selected="" @endif>Active</option>
                  <option value="0" @if(old('status')==0) selected="" @endif>Disabled</option>
                </select>
              </div>
            </div>
            <div class="form-group form-actions">
              <div class="col-sm-offset-2 col-sm-10 col-md-offset-2 col-md-10">
                <a href="{{URL::route('admin.kitchens')}}" class="btn btn-default">Back</a>
                  <button class="btn btn-success">Add Kitchen </button>
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

    });
$('.GroupGroup').select2();
  </script>

  @endsection