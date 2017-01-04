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
                List of Cancelled Orders
            </div>&nbsp;&nbsp;

        </div>

        <div class="content-wrapper">

            @include('admin.partials.flash')

            <table id="datatable-example">
                <thead>
                <tr>
                    <th tabindex="0" rowspan="1" colspan="1">Sno
                    </th>
                    <th tabindex="0" rowspan="1" colspan="1">Order ID
                    </th>
                    <th tabindex="0" rowspan="1" colspan="1">Customer
                    </th>
                    <th tabindex="0" rowspan="1" colspan="1">Date
                    </th>
                    <th tabindex="0" rowspan="1" colspan="1">Status
                    </th>
                    <th tabindex="0" rowspan="1" colspan="1">Amount
                    </th>
                    <th tabindex="0" rowspan="1" colspan="1">Payment Mode
                    </th>
                    <th tabindex="0" rowspan="1" colspan="1">Source
                    </th>
                    <th tabindex="0" rowspan="1" colspan="1">Kitchen
                    </th>
                </tr>
                </thead>

                <tfoot>
                <tr>
                    <th rowspan="1" colspan="1">Sno</th>
                    <th rowspan="1" colspan="1">Order No</th>
                    <th rowspan="1" colspan="1">Customer</th>
                    <th rowspan="1" colspan="1">Date</th>
                    <th rowspan="1" colspan="1">Status</th>
                    <th rowspan="1" colspan="1">Amount</th>
                    <th rowspan="1" colspan="1">Payment Mode</th>
                    <th rowspan="1" colspan="1">Source</th>
                    <th rowspan="1" colspan="1">Kitchen</th>

                </tr>
                </tfoot>
                <tbody>
                <?php $counter=1;?>
                @foreach($orders as $order)
                    <tr class="orders-row @if(((\Carbon\Carbon::now()->timestamp/60) - ($order->created_at->timestamp/60)) > 10){{ "delayed-orders" }}@endif @if($order->is_printed == 0){{ "to-be-printed" }}@endif" data-kot="{{ route('admin.orders.kot.get', $order->id) }}" data-invoice="{{ route('admin.orders.invoice.get', $order->id) }}">
                        <td>
                            {{$counter++}}
                        </td>
                        <td><a href="{{ route('admin.update_order', $order->id) }}">{{ $order->order_no }}</a></td>
                        <td><a href='{{route('admin.update_user',$order->user->id)}}'>{{$order->user->full_name}}</a></td>
                        <td>{{$order->created_at->format('H:i:s d F,Y')}}</td>
                        <td><span class='label {{config('bueno.color_class.'.$order->status)}}'> {{$order->statusText->name}} </span>  &nbsp; <span class='label @if($order->paymentInfo->status==3) label-success' @else label-danger' @endif>{{$order->paymentInfo->paymentStatus->name}}</span></td>
                        <td>@if($order->paymentInfo){{$order->paymentInfo->amount}}@else No Payment Info @endif</td>
                        <td>@if($order->paymentMode){{  $order->paymentMode->name }}@endif</td>
                        <td>@if($order->source){{$order->source->name}}@endif</td>
                        <td>@if($order->kitchen) {{$order->kitchen->name}} @else No Kitchen @endif</td>
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
                        "iDisplayLength": 100,
                        "aLengthMenu": [[10, 20, -1], [10, 20, "All"]]
                    });
                });

            </script>

            <style>
                table.dataTable tr.delayed-orders td {
                    background-color: #e76969 !important;
                    color: white;
                }

                table.dataTable tr.delayed-orders td a{
                    color: white;
                }

            </style>

@endsection