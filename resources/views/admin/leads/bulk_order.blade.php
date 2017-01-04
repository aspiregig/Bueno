
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
          Catering Leads 
        </div>
      </div>

      <div class="content-wrapper">
        
        <table id="datatable-example">
                    <thead>
                        <tr>
                            <th tabindex="0" rowspan="1" colspan="1">Sno
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Name
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Phone Number
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Email id
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Date & Time
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Query
                            </th>
                        </tr>
                    </thead>
                    
                    <tfoot>
                        <tr>
                            <th rowspan="1" colspan="1">Sno</th>
                            <th rowspan="1" colspan="1">Name</th>
                            <th rowspan="1" colspan="1">Phone Number</th>
                            <th rowspan="1" colspan="1">Email id</th>
                            <th tabindex="0" rowspan="1" colspan="1">Date & Time
                            </th>
                            <th rowspan="1" colspan="1">Query</th>     
                        </tr>
                    </tfoot>
                    <tbody>
                    @foreach($cater_leads as $lead)
                        <tr>
                            <td>
                              {{ $lead->id }}
                            </td>
                            <td>{{$lead->full_name}}</td>
                            <td>{{$lead->phone}}</td>
                            <td>{{$lead->email}}</td>
                            <td>{{$lead->created_at}}</td>
                            <td><a href="{{URL::route('admin.catering.view',$lead->id)}}">View Query</a></td>
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
                "aaSorting": [[ 4, "desc" ]],
                "aLengthMenu": [[10, 50, 100, -1], [10, 50, 100, "All"]]
            });
        });
    </script>

  @endsection 