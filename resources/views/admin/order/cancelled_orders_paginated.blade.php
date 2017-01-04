
@extends('admin.master')

@section('title')Cancelled Orders @endsection

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
                List of Cancelled Orders (Total - {{$orders->total()}})
            </div>
          
        </div>
        <form action="">
            <input type="text" name="keyword" placeholder="Search" value="{{ request()->has('keyword') ? request()->get('keyword') : '' }}"/>
        </form>
        <div class="content-wrapper">

            @include('admin.partials.flash')

            <table id="datatable-orders">
                <thead>
                <tr>
                    <th tabindex="0" rowspan="1" colspan="1">Select all
                    </th>
                    <th colspan="1" rowspan="1" tabindex="0" class="" ><a href="{{ sort_orders_by('order_no') }}">Order No.<i class="{{ get_sort_icon('order_no') }}"></i></a></th>
                    <th tabindex="0" rowspan="1" colspan="1">Customer
                    </th>
                    <th colspan="1" rowspan="1" tabindex="0" class="" ><a href="{{ sort_orders_by('order_date') }}">Order Date<i class="{{ get_sort_icon('order_date') }}"></i></a></th>
                    <th colspan="1" rowspan="1" tabindex="0" class="" ><a href="{{ sort_orders_by('status') }}">Status<i class="{{ get_sort_icon('status') }}"></i></a></th>
                    <th tabindex="0" rowspan="1" colspan="1">Amount
                    </th>
                    <th tabindex="0" rowspan="1" colspan="1">Coupon
                    </th>
                    <th tabindex="0" rowspan="1" colspan="1">Payment Mode
                    </th>
                    <th tabindex="0" rowspan="1" colspan="1">Locality
                    </th>
                    <th tabindex="0" rowspan="1" colspan="1">Source
                    </th>
                    <th tabindex="0" rowspan="1" colspan="1">Kitchen
                    </th>
                </tr>
                </thead>

                <tfoot>
                <tr>
                    <th rowspan="1" colspan="1"></th>
                    <th colspan="1" rowspan="1" tabindex="0" class="" ><a href="{{ sort_orders_by('order_no') }}">Order No.<i class="{{ get_sort_icon('order_no') }}"></i></a></th>
                    <th rowspan="1" colspan="1">Customer</th>
                    <th colspan="1" rowspan="1" tabindex="0" class="" ><a href="{{ sort_orders_by('order_date') }}">Order Date<i class="{{ get_sort_icon('order_date') }}"></i></a></th>
                    <th colspan="1" rowspan="1" tabindex="0" class="" ><a href="{{ sort_orders_by('status') }}">Status<i class="{{ get_sort_icon('status') }}"></i></a></th>
                    <th rowspan="1" colspan="1">Amount</th>
                    <th rowspan="1" colspan="1">Coupon</th>
                    <th rowspan="1" colspan="1">Payment Mode</th>
                    <th rowspan="1" colspan="1">Locality</th>
                    <th rowspan="1" colspan="1">Source</th>
                    <th rowspan="1" colspan="1">Kitchen</th>
                </tr>
                </tfoot>
                <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td><input type="checkbox" value="{{$order->id}}"></input></td>
                        <td><a href='{{route('admin.update_order',$order->id)}}'>{{$order->order_no}}</a></td>
                        <td>@if($order->user)<a href='{{route('admin.update_user',$order->user->id)}}'>{{$order->user->full_name}}@endif</a></td>
                        <td>{{$order->created_at->format('H:i:s d F,Y')}}</td>
                        <td><span class="label {{config('bueno.color_class.'.$order->status)}}"> {{$order->statusText->name}} </span>  &nbsp;  @if($order->paymentInfo)<span class='label @if($order->paymentInfo->status==3) label-success @else label-danger @endif'>{{$order->paymentInfo->paymentStatus->name}}</span> @endif</td>
                        <td>@if($order->paymentInfo){{$order->paymentInfo->amount}}@else NA @endif</td>
                        <td>@if($order->coupon) {{$order->coupon->code}} @else No Coupon @endif</td>
                        <td>{{$order->paymentMode->name}}</td>
                        <td>{{$order->area->name}}</td>
                        <td>{{$order->source->name}}</td>
                        <td>{{$order->kitchen->name}}</td>
                    </tr>
                @endforeach

                </tbody>
            </table>
            @if($orders)
                {!! $orders->appends(request()->except('page'))->render() !!}
            @endif
        </div>
        @endsection

        @section('script')




            <script>
                $('#datatable-orders input[type=checkbox]').change(function(event) {
                    _this = $(this);

                    checked = $('#datatable-orders input[type=checkbox]:checked');
                    ids = [];
                    $(checked).each(function(index, el) {
                        ids.push($(el).val());
                    });
                    url = "{{ route('admin.orders.mark_as_settled') }}";

                    $('#mark-settled-button').attr('href', url + '?ids=' + ids.join(',') );
                });
            </script>
@endsection