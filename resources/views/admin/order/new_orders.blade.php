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
                List of New Orders
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
                            <th tabindex="0" rowspan="1" colspan="1">Actions
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
                            <th rowspan="1" colspan="1">Actions</th>

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
                    <td>{{$order->paymentInfo->amount}}</td>
                    <td>{{$order->paymentMode->name}}</td>
                    <td>{{$order->source->name}}</td>
                    <td>{{$order->kitchen->name}}</td>
                        <td>
                            <form action="{{ route('admin.orders.pack.post') }}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" name="order_no" value="{{ $order->order_no }}"/>
                                <button class="btn btn-primary">Pack</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
        @endsection

        @section('script')


            <script type="text/javascript">

                var createPopUnder = function(url){
                    popover = window.open(url, "s", "width= 640, height= 480, left=0, top=0, resizable=yes, toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no");
                    popover.blur();
                    //popover.print();

                };
                $(function() {
                    $('#datatable-example').dataTable({
                        "sPaginationType": "full_numbers",
                        "iDisplayLength": 100,
                        "aaSorting": [[ 3, "desc" ]],
                        "bPaginate": false
                    });
                });


                $(document).ready(function(e){
                    orders = $('.orders-row.to-be-printed');

                    orders.each(function(index){
                        order = $(this);
                        var audio = new Audio('/sounds/alarm.wav');
                        audio.play();
                        var randomnumber = Math.floor((Math.random()*100)+1);
                        randomnumber = window.open( order.data('kot'),"_blank",'PopUp',randomnumber,'scrollbars=1,menubar=0,resizable=1,width=100,height=100');
                        randomnumber.print();
                        setTimeout(function(){
                            randomnumber.close();
                        }, 2000);
                        randomnumber.blur();
                        window.focus();

                        var order_number = Math.floor((Math.random()*100)+1);
                        order_print = window.open( order.data('invoice'),"_blank",'PopUp', order_number ,'scrollbars=1,menubar=0,resizable=1,width=100,height=100');
                        order_print.print();
                        setTimeout(function(){
                            order_print.close();
                        }, 2000);
                        order_print.blur();
                        window.focus();
                        //popover.print();
                    });
                });
            </script>

            <script>
                setInterval(function(){
                    location.reload();
                }, 30000);
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