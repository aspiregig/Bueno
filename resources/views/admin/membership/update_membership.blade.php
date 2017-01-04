
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
                    Update Membership
                </div>
            </div>

            <div class="content-wrapper">
                <form id="new-customer" class="form-horizontal" method="post" action="#" role="form">
                {{csrf_field()}}
                {{ method_field('PATCH') }}
                <input type="hidden" name="id" value="{{$membership->id}}">
                <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Group Name</label>
                        <div class="col-sm-10 col-md-8">
                          <input type="text" class="form-control" name="name" @if(old('name')!=null) value="{{old('name')}}" @else value="{{$membership->name}}" @endif required=""/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Min No Order</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="text" class="form-control" name="min" @if(old('min')!=null) value="{{old('min')}}" @else value="{{$membership->min}}" @endif />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Bueno Credits per order(Order %)</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="text" class="form-control" name="loyalty_points" @if(old('loyalty_points')!=null) value="{{old('loyalty_points')}}" @else value="{{$membership->loyalty_points}}" @endif />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Background Color</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="text" class="form-control jscolor" name="bg_color" @if(old('bg_color')!=null) value="{{old('bg_color')}}" @else value="{{$membership->bg_color}}" @endif />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Text Color</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="text" class="form-control jscolor" name="text_color" @if(old('text_color')!=null) value="{{old('text_color')}}" @else value="{{$membership->text_color}}" @endif />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-8">
                            <textarea class="form-control" name="description">@if(old('description')){{old('description')}}@else{{$membership->description}}@endif</textarea>
                        </div>
                    </div>
                    <div class="form-group form-actions">
                        <div class="col-sm-offset-2 col-sm-10">
                            <a href="{{URL::route('admin.memberships',$membership->id)}}" class="btn btn-default">Back</a>
                                <button type="submit" class="btn btn-success">Update Membership</button>
                            <a href="#" data-toggle="modal" data-target="#confirm-modal" class="btn btn-danger">Delete</a>
                        </div>
                    </div>
                </form>
                  
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
                  Are you sure you want to delete this Membership?
                </h4>
              </div>
              <div class="modal-body">
            Do you want to delete this Membership? All the info will be erased.
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a href="{{URL::route('admin.delete_membership',$membership->id)}}"  class="btn btn-danger">Yes, delete it</a>
              </div>
          </form>
        </div>
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