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
                    Update State 
                </div>
            </div>

            <div class="content-wrapper">

                @include('admin.partials.flash')

                <form id="new-customer" class="form-horizontal" method="post" role="form">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <input type="hidden" name="id" value="{{$state->id}}"> 
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">State Name</label>
                        <div class="col-sm-10 col-md-8">
                          <input type="text" class="form-control" name="name" @if(old('name')!=null) value="{{old('name')}}" @else value="{{$state->name}}" @endif required=""/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Status</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="has-feedback">
                                <select name="status" class="" id="OrderLocality">
                                <option value="1" @if($state->status) selected="" @endif>Active</option>
                                <option value="0" @if(!$state->status) selected="" @endif>Disabled</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-actions">
                        <div class="col-sm-offset-2 col-sm-10">
                            <a href="{{URL::route('admin.states')}}" class="btn btn-default">Back</a>
                            <button type="submit" class="btn btn-success">Update state</button>
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