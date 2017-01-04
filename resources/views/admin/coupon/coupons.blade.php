
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

        <form action="">
            <input type="text" name="keyword" placeholder="Search" value="{{ request()->has('keyword') ? request()->get('keyword') : '' }}"/>
        </form>

      <div class="content-wrapper">
          @include('admin.partials.flash')
          <table id="datatable-coupons">
                    <thead>
                        <tr>
                            <th colspan="1" rowspan="1" tabindex="0" class="" ><a href="{{ sort_coupons_by('id') }}">ID<i class="{{ get_sort_icon('id') }}"></i></a></th>
                            <th colspan="1" rowspan="1" tabindex="0" class="" ><a href="{{ sort_coupons_by('code') }}">Code<i class="{{ get_sort_icon('code') }}"></i></a></th>
                            <th tabindex="0" rowspan="1" colspan="1">Description
                            </th>
                            <th colspan="1" rowspan="1" tabindex="0" class="" ><a href="{{ sort_coupons_by('status') }}">Status<i class="{{ get_sort_icon('status') }}"></i></a></th>
                        </tr>
                    </thead>
                    
                    <tfoot>
                        <tr>
                            <th>Sno</th>
                            <th>Code</th>
                            <th>Description</th>
                            <th>Status</th>
                        </tr>
                    </tfoot>
                    <tbody>
                    @foreach($coupons as $coupon)
                        <tr>
                            <td>{{$coupon->id}}</td>
                            <td><a href="{{URL::route('admin.update_coupon',$coupon->id)}}">{{$coupon->code}}</a></td>
                            <td>{{$coupon->description}}</td>
                            <td class="center"><span class="label @if($coupon->status==1) label-success">Active @else label-danger"> Disabled @endif </span></td>
                        </tr>
                    @endforeach                                               
                    </tbody>
                </table>

          {!! $coupons->appends(request()->except('page'))->render() !!}

      </div>

  @endsection

  @section('script')
   
   
  @endsection