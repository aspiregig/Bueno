
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
                    List of NGOs
                </div>&nbsp;&nbsp;

        <a href="{{URL::route('admin.new_ngo')}}" class="new-user btn btn-primary btn-xs">
          New Ngo</a>
            </div>

            <div class="content-wrapper">
                @include('admin.partials.flash')
                <table id="datatable-example">
                    <thead>
                        <tr>
                            <th tabindex="0" rowspan="1" colspan="1" class="sorting_desc">Sno
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Name
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Description
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Default Donation Amount
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Status
                            </th>
                        </tr>
                    </thead>
                    
                    <tfoot>
                        <tr>
                            <th rowspan="1" colspan="1">Sno</th>
                            <th rowspan="1" colspan="1">Name</th>
                            <th rowspan="1" colspan="1">Description</th>
                            <th rowspan="1" colspan="1">Default Donation Amount</th>
                            <th rowspan="1" colspan="1">Status</th>
                        </tr>
                    </tfoot>
                    <tbody>
                    @foreach($ngos as $ngo)
                        <tr>
                            <td>
                                <a href="{{URL::route('admin.update_ngo',$ngo->id)}}">{{$ngo->id}}</a>
                            </td>
                            <td><a href="{{URL::route('admin.update_ngo',$ngo->id)}}">{{$ngo->name}}</a></td>
                            <td>{{$ngo->description}}</td>
                            <td class="center">{{$ngo->default_donation_amount}}</td>
                            <td class="center"><span class="label @if($ngo->status) label-success @else label-danger @endif">{{$ngo->status_text}}</span></td>
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
                "aLengthMenu": [[10, 20, 100, -1], [10, 20, 100, "All"]]
            });
        });
    </script>

  @endsection