
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
        <div class="menubar">
            <div class="sidebar-toggler visible-xs">
                <i class="ion-navicon"></i>
            </div>

            <div class="page-title">
               Catering Leads
            </div>
        </div>

        <div class="content-wrapper query-single">
            @include('admin.partials.errors')
            @include('admin.partials.flash')
            <form id="new-customer" class="form-horizontal" method="post" role="form" enctype="multipart/form-data">

                <div class="form-group">
                    <label class="col-sm-2 col-md-2 control-label">Full Name</label>
                    <div class="col-sm-10 col-md-8">
                        <div class="has-feedback">
                            {{$query->full_name}}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 col-md-2 control-label">Phone</label>
                    <div class="col-sm-10 col-md-8">
                        {{$query->phone}}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 col-md-2 control-label">Email</label>
                    <div class="col-sm-10 col-md-8">
                        {{$query->email}}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 col-md-2 control-label">Query</label>
                    <div class="col-sm-10 col-md-8">
                        {{$query->query}}
                    </div>
                </div>


            </form>

        </div>
    </div>

@endsection

@section('script')

    <script type="text/javascript">
        $(function () {

            $('#slug').select2();

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