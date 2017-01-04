
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
          List of Coupons
        </div>&nbsp;&nbsp;

        <a href="{{URL::route('admin.new_coupon')}}" class="new-user btn btn-primary btn-xs">
          Add New </a>
      </div>

      <div class="content-wrapper">
        
        <table id="datatable-example">
                    <thead>
                        <tr>
                            <th tabindex="0" rowspan="1" colspan="1">Sno
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Code
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Type
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Min Amount
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Value
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">From Date
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">To Date
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">One Time
                            </th>
                        </tr>
                    </thead>
                    
                    <tfoot>
                        <tr>
                            <th>Sno</th>
                            <th>Code</th>
                            <th>Type</th>
                            <th>Min Amount</th>
                            <th>Value</th>
                            <th>From Date</th>
                            <th>To Date</th>
                            <th>One Time</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <tr>
                            <td>
                              1
                            </td>
                            <td><a href="/update-coupon">T220</a></td>
                            <td>Amount</td>
                            <td>1.00</td>
                            <td>319.00</td>
                            <td>2015-11-01 23:40:55</td>
                            <td>2015-11-02 00:30:01</td>
                            <td>No</td>
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
    </script>

  @endsection