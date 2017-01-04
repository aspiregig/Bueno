
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
                    Add Area
                </div>
            </div>

            <div class="content-wrapper">
                <form id="new-customer" class="form-horizontal" method="post" action="#" role="form">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Area Name</label>
                        <div class="col-sm-10 col-md-8">
                          <input type="text" class="form-control" name="name" value = "{{old('name')}}"  required=""/>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Parent City</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="has-feedback">
                                <select name="city_id" class="form-control" id="OrderLocality">
                                @foreach($cities as $city)
                                <option value="{{$city->id}}" @if($city->id==old('city_id')) selected="" @endif>{{$city->name}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Pincode</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="text" class="form-control" name="pincode" value = "{{old('pincode')}}"  required=""/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Min Order Amount(INR)</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="text" class="form-control" name="min_order_amount" value = "{{old('min_order_amount')}}"  required=""/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Delivery Time (Minutes)</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="text" class="form-control" name="delivery_time" value = "{{old('delivery_time')}}"  required=""/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Area Longitude</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="text" class="form-control" name="longitude" value = "{{old('longitude')}}" />
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Area Latitude</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="text" class="form-control" name="latitude" value = "{{old('latitude')}}" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Status</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="has-feedback">
                                <select name="status" class="form-control">
                                <option value="1" @if(old('status')==1)  selected="" @endif>Active</option>
                                <option value="0"  @if(old('status')==0) selected="" @endif>Disabled</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-actions">
                        <div class="col-sm-offset-2 col-sm-10">
                            <a href="{{URL::route('admin.areas')}}" class="btn btn-default">Back</a>
                            <button type="submit" class="btn btn-success">Add Area</button>
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


           
        });
    </script>

  @endsection