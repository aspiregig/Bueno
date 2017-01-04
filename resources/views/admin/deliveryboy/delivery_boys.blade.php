
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
          List of Delivery Boys
        </div>&nbsp;&nbsp;

        <a href="{{URL::route('admin.new_delivery_boy')}}" class="new-user btn btn-primary btn-xs">
          Add New </a>
      </div>

      <div class="content-wrapper">
          @include('admin.partials.flash')
          <table id="datatable-example">
                    <thead>
                        <tr>
                            <th tabindex="0" rowspan="1" colspan="1">Sno
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Name
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Phone Number
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Kitchen Name
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Vehicle
                            </th>
                           
                        </tr>
                    </thead>
                    
                    <tfoot>
                        <tr>
                            <th rowspan="1" colspan="1">Sno</th>
                            <th rowspan="1" colspan="1">Name</th>
                            <th rowspan="1" colspan="1">Phone Number</th>
                            <th rowspan="1" colspan="1">Kitchen Name</th>
                            <th rowspan="1" colspan="1">Vehicle</th>

                           
                        </tr>
                    </tfoot>
                    <tbody>
                    @foreach($delivery_boys as $delivery_boy)
                        <tr>
                            <td>
                              {{$delivery_boy->id}}
                            </td>
                            <td><a href="{{URL::route('admin.update_delivery_boy',$delivery_boy->id)}}">{{$delivery_boy->full_name}}</a></td>
                            <td>{{$delivery_boy->phone}}</td>
                            <td>{{$delivery_boy->kitchen->name}}</td>
                            <td>{{$delivery_boy->vehicle_name}} : {{$delivery_boy->vehicle_number}}</td>
                        </tr>
                    @endforeach

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