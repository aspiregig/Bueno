
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
                    Add a new Cuisine
                </div>
            </div>

            <div class="content-wrapper">
                <form id="new-customer" class="form-horizontal" method="post" role="form">
                {{csrf_field()}}
                <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Cuisine Name</label>
                        <div class="col-sm-10 col-md-8">
                          <input type="text" class="form-control" name="name" value="{{old('name')}}" required=""/>
                        </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 col-md-2 control-label">Sequence</label>
                    <div class="col-sm-10 col-md-8">
                        <input type="text" class="form-control" name="sequence" value="{{old('sequence')}}" required=""/>
                    </div>
                </div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 col-md-2 control-label">Cuisine Status</label>
                        <div class="col-sm-10 col-md-8">
                            <select class="form-control"  id="status" name="status">
                                <option value="1" @if(old('status')==1) selected="" @endif>Active</option>
                                <option value="0" @if(old('status')==0) selected="" @endif>Disabled</option>
                            </select>
                        </div>
                    </div>
                <div class="form-group form-actions">
                    <div class="col-sm-offset-2 col-sm-10">
                        <a href="{{URL::route('admin.cuisines')}}" class="btn btn-default">Cancel</a>
                        <button type="submit" class="btn btn-success">Add Cuisine</button>
                    </div>
                </div>
                </form>
                  
            </div>
        </div>

  @endsection

  @section('script')

  <script type="text/javascript">
        $('#GroupGroup').select2();
    </script>

  @endsection