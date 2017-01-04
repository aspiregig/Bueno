
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
          List of Home Sliders
        </div>

        <a href="{{URL::route('admin.new_home_slider')}}" class="new-user btn btn-primary btn-xs" style="margin-left:10px;">
          Add New</a>
      </div>

      <div class="content-wrapper">
          @include('admin.partials.flash')
          <table id="datatable-example">
                    <thead>
                        <tr>
                            <th tabindex="0" rowspan="1" colspan="1">Slider ID
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Image
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Description
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Link to
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Status
                            </th>
                        </tr>
                    </thead>
                    
                    <tfoot>
                        <tr>
                            <th rowspan="1" colspan="1">Alias Name</th>
                            <th rowspan="1" colspan="1">Image</th>
                            <th rowspan="1" colspan="1">Description</th>
                            <th rowspan="1" colspan="1">Link To</th>
                            <th rowspan="1" colspan="1">Status</th>


                        </tr>
                    </tfoot>
                    <tbody>
                      @foreach($sliders as $home_slider)
                        <tr>
                            <td><a href="{{URL::route('admin.update_home_slider',$home_slider->id)}}">Slider {{$home_slider->id}}</a></td>
                            <td style="background-image:url({{route('photo.home_slider',$home_slider->image_url)}});  background-repeat: no-repeat; background-position: center;background-size: contain; height:110px"></td>
                            <td>{{$home_slider->description}}</td>
                            <td>{{$home_slider->link_url}}</td>
                            <td>@if($home_slider->status)<label class="label label-success"> Active @else <label class="label label-danger"> Disable @endif</label></td>
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