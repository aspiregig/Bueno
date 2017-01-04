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
  @include('admin.partials.errors')
      <div class="menubar">
        <div class="sidebar-toggler visible-xs">
          <i class="ion-navicon"></i>
        </div>

        <div class="page-title">
          Add a new Delivery Boy 
        </div>
      </div>

      <div class="content-wrapper">

          @include('admin.partials.flash')

        <form id="new-customer" class="form-horizontal" method="post" role="form">
            {{csrf_field()}}
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Name</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" name="full_name" required="" value="{{old('full_name')}}" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Phone</label>
              <div class="col-sm-10 col-md-8">
                  <input type="text" class="form-control" name="phone" required="" value="{{old('phone')}}"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Kitchen</label>
              <div class="col-sm-10 col-md-8">
                <div class="has-feedback">
                <select name="kitchen_id" class="form-control" id="GroupGroup">
                @foreach($kitchens as $kitchen)
                <option value="{{$kitchen->id}}" @if(old('kitchen_id')==$kitchen->id) selected="" @endif>{{$kitchen->name}}</option>
                @endforeach
                </select>
              </div>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Vehicle Name</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" name="vehicle_name" value="{{old('vehicle_name')}}"/>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Vehicle Number</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" name="vehicle_number" value="{{old('vehicle_number')}}"/>
              </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">Jooleh Uid</label>
                <div class="col-sm-10 col-md-8">
                    <input type="text" class="form-control" name="jooleh_uid" value="{{old('jooleh_uid')}}" required=""/>
                </div>
            </div>
            <div class="form-group form-actions">
              <div class="col-sm-offset-2 col-sm-10">
                <a href="{{URL::route('admin.delivery_boys')}}" class="btn btn-default">Back</a>
                  <button class="btn btn-success">Add Delivery Boy</button>
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

      // tags with select2
      $("#customer-tags").select2({
        placeholder: 'Select tags or add new ones',
        tags:["supplier", "lead", "client", "friend", "developer", "customer"],
        tokenSeparators: [",", " "]
      });

      // masked input example using phone input
      $(".mask-phone").mask("(999) 999-9999");
      $(".mask-cc").mask("9999 9999 9999 9999");
    });
  </script>


  @endsection