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
            <div class="menubar" >
                <div class="sidebar-toggler visible-xs">
                    <i class="ion-navicon"></i>
                </div>

                <div class="page-title">
                    Update Ngo
                </div>
            </div>
            <div class="content-wrapper">
            @include('admin.partials.flash')
            @include('admin.partials.errors')
                <form id="new-customer" class="form-horizontal" method="post" role="form" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                <input type="hidden" name="id" value="{{$ngo->id}}"> 
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-8">
                          <input type="text" class="form-control " name="name" required=""  @if(old('name')!=null) value="{{old('name')}}" @else  value="{{$ngo->name}}" @endif/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-8">
                          <textarea type="text" class="form-control " name="description" />{{$ngo->description}} </textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Display picture</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="well">
                                <div class="pic">
                                    <img src="{{route('photo.ngos',$ngo->display_image_url)}}" class="img-responsive" />
                                </div>
                                <div class="control-group">
                                    <label>
                                        Choose a picture:
                                    </label>
                                    <input name="display_image_url" type="file">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Default Donation amount</label>
                        <div class="col-sm-10 col-md-8">
                          <input type="number" class="form-control" name="default_donation_amount" required="" value="{{$ngo->default_donation_amount}}" min="1"/>
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-2 col-md-2 control-label">Status</label>
                      <div class="col-sm-10 col-md-8">
                        <select class="form-control"  id="status" name="status">
                          <option value="1" @if($ngo->status) selected="" @endif>Active</option>
                          <option value="0" @if(!$ngo->status) selected="" @endif>Disabled</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group form-actions">
                        <div class="col-sm-offset-2 col-sm-10">
                            <a href="{{URL::route('admin.ngos')}}" class="btn btn-default">Cancel</a>
                            <button type="submit" class="btn btn-success">Update NGO</button>
                            <a href="#" data-toggle="modal" data-target="#confirm-modal" class="btn btn-danger">Delete</a>
                        </div>
                    </div>
                </form>
                    
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
            Do you want to delete this Ngo? All the info will be erased.
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a href="{{URL::route('admin.delete_ngo',$ngo->id)}}"  class="btn btn-danger">Yes, delete it</a>
              </div>
          </form>
        </div>
      </div>
  </div>

  @endsection

