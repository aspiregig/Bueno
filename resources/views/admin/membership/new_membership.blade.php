
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
                    Add a new Membership
                </div>
            </div>

            <div class="content-wrapper">
                <form id="new-customer" class="form-horizontal" method="post" role="form">
                {{csrf_field()}}
                <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Membership Name</label>
                        <div class="col-sm-10 col-md-8">
                          <input type="text" class="form-control" name="name" value="{{old('name')}}" />
                        </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 col-md-2 control-label">Min No Order</label>
                    <div class="col-sm-10 col-md-8">
                        <input type="text" class="form-control" name="min" value="{{old('min')}}" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 col-md-2 control-label">Bueno Credits per order(Order %)</label>
                    <div class="col-sm-10 col-md-8">
                        <input type="text" class="form-control" name="loyalty_points" value="{{old('loyalty_points')}}" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 col-md-2 control-label">Background Color</label>
                    <div class="col-sm-10 col-md-8">
                        <input type="text" class="form-control jscolor" name="bg_color" value="{{old('bg_color')}}" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 col-md-2 control-label">Text Color</label>
                    <div class="col-sm-10 col-md-8">
                        <input type="text" class="form-control jscolor" name="text_color" value="{{old('text_color')}}" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 col-md-2 control-label">Description</label>
                    <div class="col-sm-10 col-md-8">
                        <textarea class="form-control" name="description">{{old('description')}}</textarea>
                    </div>
                </div>
                <div class="form-group form-actions">
                    <div class="col-sm-offset-2 col-sm-10">
                        <a href="{{URL::route('admin.memberships')}}" class="btn btn-default">Cancel</a>
                        <button type="submit" class="btn btn-success">Add Membership</button>
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
        $('#GroupGroup').select2();
    </script>

  @endsection