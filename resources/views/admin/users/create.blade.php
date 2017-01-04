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
          Add a new User 
        </div>
      </div>

      <div class="content-wrapper">
        <form id="new-user" class="form-horizontal" method="post" role="form" enctype="multipart/form-data">
                    {{ csrf_field() }}
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">First name</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" name="first_name" required="" value="{{old('first_name')}}"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Last name</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" class="form-control" name="last_name" value="{{old('last_name')}}"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Display picture</label>
              <div class="col-sm-10 col-md-8">
                <input type="file" name="avatar_url" value="{{old('avatar_url')}}" accept="image/*" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Email address</label>
              <div class="col-sm-10 col-md-8">
                  <input type="email" class="form-control" name="email" value="{{old('email')}}"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Password</label>
              <div class="col-sm-10 col-md-8">
                  <input type="password" class="form-control" name="password" required="" />
              </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-md-2 control-label">Confirm Password</label>
                <div class="col-sm-10 col-md-8">
                    <input type="password" class="form-control" name="confirm_password" required=""/>
                </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Phone number</label>
              <div class="col-sm-10 col-md-8">
                <div class="has-feedback">
                <input type="text" class="form-control" name="phone" required="" value="{{old('phone')}}"/>
              </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 col-md-2 control-label">Acesss Level<small style="color:red;">*</small></label>
              <div class="col-sm-10 col-md-8">
                <select class="form-control" name="group_id" id="GroupGroup">
                    @foreach($groups as $group)
                        <option value="{{$group->id}}" @if(old('group_id') == $group->id) selected="" @endif>{{ $group->name }}</option>
                    @endforeach
                </select>
              </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 col-md-2 control-label">Status</label>
                <div class="col-sm-10 col-md-8">
                    <select class="form-control"  id="status" name="status">
                        <option value="1" @if(old('status')==1) selected="" @endif>Active</option>
                        <option value="0" @if(old('status')==0) selected="" @endif>Disabled</option>
                    </select>
                </div>
            </div>
            <div class="form-group form-actions">
              <div class="col-sm-offset-2 col-sm-10">
                <a href="{{URL::route('admin.users')}}" class="btn btn-default">Back</a>
                  <button class="btn btn-success">Create User</button>
              </div>
            </div>
        </form>
      </div>
    </div>
  </div>

  
  
  @endsection

  @section('script')
  <script type="text/javascript">
    $(function () {
      $('#new-user').validate({
        rules: {
          "first_name": {
            required: true
          },
        highlight: function (element) {
          $(element).closest('.form-group').removeClass('success').addClass('error');
        },
        success: function (element) {
          element.addClass('valid').closest('.form-group').removeClass('error').addClass('success');
        }
      }
    });
    });
      </script>
  @endsection

  