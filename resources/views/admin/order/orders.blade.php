
@extends('admin.master')

  @section('title')All Orders @endsection

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
                    List of Orders (Total - {{$orders->total()}})
                </div>

        <a href="{{URL::route('admin.new_order')}}" class="new-user btn btn-primary btn-xs" style="margin-left:10px;">
          New Quick Order</a>
                <div class="period-select hidden-xs">
                    <form  method="post" role="form" action="{{URL::route('admin.orders.export')}}">
                        {{ csrf_field() }}
                        <div class="input-daterange">
                            <div class="input-group input-group-sm">
                <span class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </span>
                                <input name="start" type="text" class="form-control start-date" value="{{$date['start']}}"  required=""/>
                            </div>


                            <p class="pull-left">to</p>

                            <div class="input-group input-group-sm">
                <span class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </span>
                                <input name="end" type="text" class="form-control end-date" value="{{$date['end']}}" required=""/>
                            </div>
                            <button type="submit" style="margin-left:10px;"class="btn btn-primary"> Export</button>
                        </div>
                    </form>
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
                            <!--<th tabindex="0" rowspan="1" colspan="1">Select all--}}
                            </th>-->
                            <th colspan="1" rowspan="1" tabindex="0" class="" ><a href="{{ sort_orders_by('order_no') }}">Order No.<i class="{{ get_sort_icon('order_no') }}"></i></a></th>
                            <th tabindex="0" rowspan="1" colspan="1">Customer
                            </th>
                            <th colspan="1" rowspan="1" tabindex="0" class="" ><a href="{{ sort_orders_by('order_date') }}">Order Date<i class="{{ get_sort_icon('order_date') }}"></i></a></th>
                            <th colspan="1" rowspan="1" tabindex="0" class="" ><a href="{{ sort_orders_by('status') }}">Status<i class="{{ get_sort_icon('status') }}"></i></a></th>
                            <th tabindex="0" rowspan="1" colspan="1">Amount
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Coupon
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Credits Used
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Credit Add
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Payment Mode
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Locality
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Kitchen
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Source
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Travel Distance
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Total Time
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Rider Time
                            </th>
                            <th tabindex="0" rowspan="1" colspan="1">Feedback
                            </th>

                        </tr>
                    </thead>
                    
                    <tfoot>
                        <tr>
                            <!--<th rowspan="1" colspan="1"></th>-->
                            <th colspan="1" rowspan="1" tabindex="0" class="" ><a href="{{ sort_orders_by('order_no') }}">Order No.<i class="{{ get_sort_icon('order_no') }}"></i></a></th>
                            <th rowspan="1" colspan="1">Customer</th>
                            <th colspan="1" rowspan="1" tabindex="0" class="" ><a href="{{ sort_orders_by('order_date') }}">Order Date<i class="{{ get_sort_icon('order_date') }}"></i></a></th>
                            <th colspan="1" rowspan="1" tabindex="0" class="" ><a href="{{ sort_orders_by('status') }}">Status<i class="{{ get_sort_icon('status') }}"></i></a></th>
                            <th rowspan="1" colspan="1">Amount</th>
                            <th rowspan="1" colspan="1">Coupon</th>
                            <th rowspan="1" colspan="1">Credits Used</th>
                            <th rowspan="1" colspan="1">Credit Add</th>
                            <th rowspan="1" colspan="1">Payment Mode</th>
                            <th rowspan="1" colspan="1">Locality</th>
                            <th rowspan="1" colspan="1">Kitchen</th>
                            <th rowspan="1" colspan="1">Source</th>
                            <th rowspan="1" colspan="1">Travel Distance</th>
                            <th rowspan="1" colspan="1">Total Time</th>
                            <th rowspan="1" colspan="1">Rider Time</th>
                            <th rowspan="1" colspan="1">Feedback</th>
                        </tr>
                    </tfoot>
                    <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <!--<td><input type="checkbox" value="{{$order->id}}"/></td>-->
                        <td><a href='{{route('admin.update_order',$order->id)}}'>{{$order->order_no}}</a></td>
                        <td><a href='{{route('admin.update_user',$order->user->id)}}'>{{$order->user->full_name}}</a></td>
                        <td>{{$order->created_at->format('H:i:s d F,Y')}}</td>
                        <td>@if($order->statusText)<span class="label {{config('bueno.color_class.'.$order->status)}}"> {{$order->statusText->name}} </span>@endif  &nbsp;@if($order->paymentInfo) <span class='label @if($order->paymentInfo->status==3) label-success @else label-danger @endif'>{{$order->paymentInfo->paymentStatus->name}}</span> @endif</td>
                        <td>@if($order->paymentInfo){{$order->paymentInfo->amount}}@endif</td>
                        <td>@if($order->coupon) {{$order->coupon->code}} @else No Coupon @endif</td>
                        <td>{{$order->redeem_points}}</td>
                        <td>{{ $order->paymentInfo->cashback_credited==0?$order->paymentInfo->cashback_buff: $order->paymentInfo->cashback_credited}}</td>
                        <td>{{$order->paymentMode->name}}</td>
                        <td>{{$order->area->name}}</td>
                        <td>{{$order->kitchen->name}}</td>
                        <td>{{$order->source->name}}</td>
                        <td>@if($order->travel_distance) {{($order->travel_distance / 1000)}}@endif</td>
                        <td>@if($order->total_delivery_time) @if($order->total_delivery_time > 60)<span class="label label-danger">{{ceil($order->total_delivery_time)}}</span> @else {{ceil($order->total_delivery_time)}}@endif @endif</td>
                        <td>@if($order->rider_delivery_time) @if($order->rider_delivery_time > 45)<span class="label label-danger">{{ceil($order->rider_delivery_time)}}</span> @else {{ceil($order->rider_delivery_time)}}@endif @endif</td>
                        <td>{{ $order->feedback_on_missed_call }}</td>
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