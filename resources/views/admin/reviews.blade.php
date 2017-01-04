
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

  <!-- Content -->
    <div id="content">
      <div class="menubar">
                <div class="sidebar-toggler visible-xs">
                    <i class="ion-navicon"></i>
                </div>
                
        <div class="page-title">
          List of Cities
        </div>
      </div>

      <div class="content-wrapper">
        
        <table id="datatable-example">
                    <thead>
                        <tr>
                            <th tabindex="0" rowspan="1" colspan="1">Sno
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Username
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Meal Name
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Review
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Rating
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Reviewed on
                            </th>
                        </tr>
                    </thead>
                    
                    <tfoot>
                        <tr>
                            <th rowspan="1" colspan="1">Sno</th>
                            <th rowspan="1" colspan="1">Username</th>
                            <th rowspan="1" colspan="1">Meal Name</th>
                            <th rowspan="1" colspan="1">Review</th>
                            <th rowspan="1" colspan="1">Rating</th>
                            <th rowspan="1" colspan="1">Reviewd on</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <tr>
                            <td>
                              1
                            </td>
                            <td><a href="/user-profile">Jeff</a></td>
                            <td>Chicken Seekh Tawa Masala with Roomali Roti</td>
                            <td>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</td>                            
                            <td><div id="raty"></div></td>
                            <td>2015-11-01 18:27:57</td>
                        </tr>                                                
                    </tbody>
                </table>

      </div>

  @endsection

  @section('script')

   
  <script type="text/javascript">
        $(function() {
            $('#datatable-example').dataTable({
                "sPaginationType": "full_numbers",
                "iDisplayLength": 10,
          "aLengthMenu": [[10, 50, 100, -1], [10, 50, 100, "All"]]
            });
        });

        // jQuery rating
      $('#raty').raty({
        path: 'images/raty',
        score: 4
      });
    </script>

  @endsection