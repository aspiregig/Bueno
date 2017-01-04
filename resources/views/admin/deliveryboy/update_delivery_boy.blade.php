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
  @include('admin.partials.flash')
      <div class="menubar">
        <div class="sidebar-toggler visible-xs">
          <i class="ion-navicon"></i>
        </div>

        <div class="page-title">
          Update Delivery Boy 
        </div>
      </div>

      <div class="content-wrapper">
        <form id="new-customer" class="form-horizontal" method="post" action="#" role="form">
        {{csrf_field()}}
        {{ method_field('PATCH') }}
        <input type="hidden" name="id" value="{{$delivery_boy->id}}"> 
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">First name</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" name="full_name" @if(old('full_name')!=null) value="{{old('full_name')}}" @else value="{{$delivery_boy->full_name}}"@endif  required=""/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Phone Number</label>
              <div class="col-sm-10 col-md-8">
                  <input type="text" class="form-control" name="phone" @if(old('phone')!=null) value="{{old('phone')}}" @else value="{{$delivery_boy->phone}}" @endif required=""/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Kitchen</label>
              <div class="col-sm-10 col-md-8">
                <div class="has-feedback">
                <select name="kitchen_id" class="form-control" >
                @foreach($kitchens as $kitchen)
                <option value="{{$kitchen->id}}" @if($kitchen->id == $delivery_boy->kitchen_id) selected="" @endif>{{$kitchen->name}}</option>
                @endforeach
                </select>
              </div>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Vehicle Name</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" name="vehicle_name" @if(old('vehicle_name')!=null) value="{{old('vehicle_name')}}" @else value="{{$delivery_boy->vehicle_name}}" @endif />
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Vehicle Number</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" name="vehicle_number" @if(old('vehicle_number')!=null) value="{{old('vehicle_number')}}" @else value="{{$delivery_boy->vehicle_number}}" @endif />
              </div>
            </div>
            <div class="form-group form-actions">
              <div class="col-sm-offset-2 col-sm-10">
                <a href="{{URL::route('admin.delivery_boys')}}" class="btn btn-default">Back</a>
                  <button class="btn btn-success">Update</button>
                  <a href="#" data-toggle="modal" data-target="#confirm-modal" class="btn btn-danger">Delete</a>
              </div>
            </div>
        </form>
      </div>
    </div>
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
            Do you want to delete this Delivery Boy Details? All the info will be erased.
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a href="{{URL::route('admin.delete_delivery_boy',$delivery_boy->id)}}"  class="btn btn-danger">Yes, delete it</a>
              </div>
          </form>
        </div>
      </div>
  </div>
  
  @endsection

  @section('script')
    <script type="text/javascript">
    $(function () {

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