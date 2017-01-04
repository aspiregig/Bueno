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
                    New Ngo
                </div>
            </div>
            <div class="content-wrapper">
            @include('admin.partials.flash')
            @include('admin.partials.errors')
                <form id="new-ngo" class="form-horizontal" method="post" role="form" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-8">
                          <input type="text" class="form-control " name="name" required="" value="{{old('name')}}"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-8">
                          <textarea type="text" class="form-control " name="description" required=""/>{{old('description')}} </textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Display picture</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="well">
                            Format : 767 x 493 <br/>
                File Size  : {{config('bueno.image.ngo_file_size_1').'kb - '.config('bueno.image.ngo_file_size_2').'kb'}}
                                <div class="pic">
                                </div>
                                <div class="control-group">
                                    <label for="post_featured_image">
                                        Choose a picture:
                                    </label>
                                    <input id="post_featured_image" name="display_image_url" type="file" accept="image/*" required="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Default Donation amount</label>
                        <div class="col-sm-10 col-md-8">
                          <input type="number" class="form-control" name="default_donation_amount" required="" value="{{old('default_donation_amount')}}" min="1" />
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-2 col-md-2 control-label">Status</label>
                      <div class="col-sm-10 col-md-8">
                        <select class="form-control"  id="status" name="status">
                          <option value="1">Active</option>
                          <option value="0">Disabled</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group form-actions">
                        <div class="col-sm-offset-2 col-sm-10">
                            <a href="{{URL::route('admin.ngos')}}" class="btn btn-default">Back</a>
                            <button type="submit" class="btn btn-success">Add NGO</button>
                        </div>
                    </div>
                </form>
                    
            </div>

  @endsection

  @section('script')
  <script type="text/javascript">

        // Form validation
      $('#new-ngo').validate({
        rules: {
          "name": {
            required: true
          },
        },
        highlight: function (element) {
          $(element).closest('.form-group').removeClass('success').addClass('error');
        },
        success: function (element) {
          element.addClass('valid').closest('.form-group').removeClass('error').addClass('success');
        }
      });

  </script>
  @endsection

