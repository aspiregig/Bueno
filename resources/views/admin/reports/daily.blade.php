
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
                Daily Report
            </div>
        </div>

        <div class="content-wrapper">
            <form id="new-customer" class="form-horizontal" method="post" role="form">
                {{csrf_field()}}
                <div class="form-group">
                    <label class="col-sm-2 col-md-2 control-label">Start Date</label>
                    <div class="input-group input-group-sm " style="max-width: 300px;padding-left: 14px;">
                <span class="input-group-addon" >
                  <i class="fa fa-calendar-o"></i>
                </span>
                        <input name="start" type="text" class="form-control start-date" required=""/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 col-md-2 control-label">End Date</label>
                    <div class="input-group input-group-sm " style="max-width: 300px;padding-left: 14px;">
                <span class="input-group-addon" >
                  <i class="fa fa-calendar-o"></i>
                </span>
                        <input name="end" type="text" class="form-control end-date" required=""/>
                    </div>
                </div>
                <div class="form-group form-actions">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-success">Download Report</button>
                    </div>
                </div>
            </form>

        </div>
    </div>


@endsection