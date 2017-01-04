@extends('admin.master')

  @section('title') Bueno @endsection

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
          Bueno Admin Panel
        </div>
      </div>

      <div class="content-wrapper">
          <div class="" id="welcome-modal" tabindex="-1" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
              <header>
                Welcome to Bueno Admin Panel!
              </header>
            </div>
        </div>
      </div>
  </div>
    </div>

  @endsection

  @section('script')

  <script type="text/javascript">
    $(function () {     
          $('#datatable-example').dataTable({
                "sPaginationType": "full_numbers",
                "iDisplayLength": 20,
          "aLengthMenu": [[20, 50, 100, -1], [20, 50, 100, "All"]]
            });
    });
  </script>
  
  @endsection



