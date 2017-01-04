
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
                Add a new Press
            </div>
        </div>

        <div class="content-wrapper">
            <form id="new-customer" class="form-horizontal" method="post" action="#" role="form">
                {{csrf_field()}}
                <div class="form-group">
                    <label class="col-sm-2 col-md-2 control-label">Title</label>
                    <div class="col-sm-10 col-md-8">
                        <input type="text" class="form-control" name="title"  value="{{old('title')}}"  required=""/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 col-md-2 control-label">Source Name</label>
                    <div class="col-sm-10 col-md-8">
                        <input type="text" class="form-control" name="source_name" value="{{old('source_name')}}"  required=""/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 col-md-2 control-label">Date</label>
                    <div class="col-sm-10 col-md-8">
                        <input type="text" class="form-control" name="date"  value="{{old('date')}}" required=""/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 col-md-2 control-label">URL</label>
                    <div class="col-sm-10 col-md-8">
                        <input type="text" class="form-control" name="url"  value="{{old('url')}}" required=""/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 col-md-2 control-label">Image Url</label>
                    <div class="col-sm-10 col-md-8">
                        <input type="text" class="form-control" name="image_url"  value="{{old('image_url')}}"  required="" />
                    </div>
                </div>
                <div class="form-group form-actions">
                    <div class="col-sm-offset-2 col-sm-10">
                        <a href="{{URL::route('admin.press')}}" class="btn btn-default">Back</a>
                        <button type="submit" class="btn btn-success">Create Press</button>
                        <a href="#" data-toggle="modal" data-target="#confirm-modal" class="btn btn-danger">Delete</a>
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