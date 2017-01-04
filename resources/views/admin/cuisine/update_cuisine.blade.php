
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
                    Update Cuisine
                </div>
            </div>

            <div class="content-wrapper">
                <form id="new-customer" class="form-horizontal" method="post" role="form">
                    {{csrf_field()}}
                    <div class="form-group">
                        {{ method_field('PATCH') }}
                        <input type="hidden" name="id" value="{{$cuisine->id}}">
                        <label class="col-sm-2 col-md-2 control-label">Cuisine Name</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="text" class="form-control" name="name" @if(old('name')) value="{{old('name')}}" @else value="{{$cuisine->name}}" @endif/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Sequence</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="text" class="form-control" name="sequence" @if(old('sequence')) value="{{old('sequence')}}" @else value="{{$cuisine->sequence}}" @endif/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 col-md-2 control-label">Cuisine Status</label>
                        <div class="col-sm-10 col-md-8">
                            <select class="form-control"  id="status" name="status">
                                <option value="1" @if(old('status')==1) selected="" @elseif($cuisine->status==1) selected @endif>Active</option>
                                <option value="0" @if(old('status')===0) selected="" @elseif($cuisine->status==0) selected @endif>Disabled</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group form-actions">
                        <div class="col-sm-offset-2 col-sm-10">
                            <a href="{{URL::route('admin.categories')}}" class="btn btn-default">Cancel</a>
                            <button type="submit" class="btn btn-success">Update Cuisine</button>
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
                  Are you sure you want to delete this Cuisine?
                </h4>
              </div>
              <div class="modal-body">
            Do you want to delete this Cuisine? All the info will be erased.
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a href="{{URL::route('admin.delete_cuisine',$cuisine->id)}}"  class="btn btn-danger">Yes, delete it</a>
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