
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
          Career Leads 
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
                            <th tabindex="0" rowspan="1" colspan="1">Applied for post
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">CV
                            </th>

                           
                        </tr>
                    </thead>
                    
                    <tfoot>
                    
                        <tr>
                            <th rowspan="1" colspan="1">Sno</th>
                            <th rowspan="1" colspan="1">Name</th>
                            <th rowspan="1" colspan="1">Phone Number</th>
                            <th rowspan="1" colspan="1">Email id</th>
                            <th rowspan="1" colspan="1">Applied for post</th>
                            <th rowspan="1" colspan="1">CV</th>

                           
                        </tr>
                    </tfoot>
                    <tbody>
                    <?php $count=1?>
                    @foreach($career_leads as $lead)
                        <tr>
                            <td>
                              {{$count++}}
                            </td>
                            <td>{{$lead->full_name}}</td>
                            <td>{{$lead->phone}}</td>
                            <td>{{$lead->email}}</td>
                            <td>{{$lead->job_post}}</td>
                            <td><a href="{{$lead->cv_url}}" class="ion-arrow-down-a" style="font-size:30px;" download></a></td>
                            
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