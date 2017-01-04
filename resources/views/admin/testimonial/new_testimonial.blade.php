
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
  @include('admin.partials.flash')
    @include('admin.partials.errors')
            <div class="menubar">
                <div class="sidebar-toggler visible-xs">
                    <i class="ion-navicon"></i>
                </div>

                <div class="page-title">
                    Add a new Testimonial
                </div>
            </div>

            <div class="content-wrapper">
                <form id="new-customer" class="form-horizontal" method="post"  role="form">
                    {{ csrf_field() }}
                <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Full Name</label>
                        <div class="col-sm-10 col-md-8">
                          <input type="text" class="form-control" name="full_name" required="" value="{{old('full_name')}}"/>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Content</label>
                        <div class="col-sm-10 col-md-8">
                          <textarea class="form-control" name="content" required="">{{old('content')}}</textarea> 
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="text" class="form-control" name="special_text" required="" value="{{old('special_text')}}" placeholder="review on Zomato | 23rd November 2015"/>
                        </div>
                    </div>


                    
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Status</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="has-feedback">
                                <select name="status" class="form-control" id="OrderLocality">
                                <option value="0" @if(old('status')==0) selected="" @endif >Disable</option>
                                <option value="1" @if(old('status')==1) selected="" @endif >Active</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group form-actions">
                        <div class="col-sm-offset-2 col-sm-10">
                            <a href="{{URL::route('admin.testimonials')}}" class="btn btn-default">Back</a>
                            <button type="submit" class="btn btn-success">Add Testimonial</button>
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