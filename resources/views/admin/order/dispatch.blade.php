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
                Dispatch Order
            </div>&nbsp;&nbsp;

        </div>

        <div class="content-wrapper">

            @include('admin.partials.flash')
            <p>Please scan the barcode or enter order number</p>
            <form action="{{ route('admin.orders.dispatch.post') }}" method="POST">
                {{ csrf_field() }}
                <input type="number" class="barcode-scan" name="order_no"/>
                <input type="submit" value="Dispatch"/>
            </form>

        </div>
        @endsection

@section('script')
            <script type="text/javascript">
                $(window).load(function(){
                    $('.barcode-scan').focus();
                });

        </script>
            @if(session('order_id'))
            <script>
                order_url = '{!! route('admin.orders.invoice.get',  session('order_id'))!!}';
                var randomnumber = Math.floor((Math.random()*100)+1);
                order_print = window.open( order_url,"_blank",'PopUp',randomnumber,'scrollbars=1,menubar=0,resizable=1,width=100,height=100');
                order_print.print();
                setTimeout(function(){
                    order_print.close();
                }, 2000);
                order_print.blur();
                window.focus();
            </script>
    @endif

@endsection
