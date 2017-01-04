
@extends('layouts.master')

@section('content')

<section class="title_sec gray-dim-bg">
    <div class="container more">
        <div class="row">
            <div class="col-xs-12">

                <div class="main-sec">
                </div> <!-- main-sec ends -->

            </div> <!-- col-xs-12 ends -->
        </div> <!-- row ends -->
    </div> <!-- container ends -->
</section> <!-- title_sec ends -->

<section class="paddingbottom-xlg marginbottom-xlg">

    <div class="container more">
        <div class="row">
            <div class="col-xs-12">

                <div class="col-xs-12 account_sec my_account_sec order_history_sec">

                    <section class="title_sec white-bg dashed_border col-xs-12 no-padding">
                        <div class="main-sec col-xs-12">
                            <div class="col-sm-12 col-md-8 left-sec">
                                <h2 class="style_header_loud">Order History</h2>
                            </div> <!-- left-sec ends -->
                            <div class="col-sm-12 col-md-4 right-sec">
                                <p class="normal_para lines">
                                    <small>
                                        @include('partials.user_links')
                                    </small>
                                </p>
                            </div> <!-- right-sec ends -->
                        </div> <!-- main-sec ends -->
                    </section> <!-- title_sec ends -->

                    <div class="col-xs-12 no-padding">
                        <div class="table-responsive cart_table_responsive">
                            @if(auth()->user()->orders->count())
                            <table class="table cart_table order_history">
                                <thead>
                                <tr>
                                    <th class="text-center">Order Number</th>
                                    <th class="text-center">Order Date</th>
                                    <th class="text-center">Address</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Tracking URL</th>
                                    <th class="text-center">Details</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($orders as $order)
                                <tr class="text-center">
                                    <td>{{ $order->order_no }}</td>
                                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{  $order->delivery_address }}</td>
                                    <td>{{ $order->statusText->name }}</td>
                                    <td>
                                        @if($order->status == 3 && $order->tracking_url)
                                            <a href="{{ $order->tracking_url }}">TRACK</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td><a href="{{ route('users.orders.single.get', $order->id) }}">View Order</a></td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table> <!-- cart_table ends -->
                            @else
                                <div class="col-xs-12 placeholder_message">
                                       <h4 class="normal_header"><i class="ion-android-alert paddingright-xs"></i>Sorry, there are no results to show.</h4>
                                   </div> <!-- placeholder_message ends -->
                            @endif
                        </div> <!-- cart_table_responsive ends -->
                        <div class="text-center">
                            {!! $orders->appends(request()->except('page'))->render() !!}
                        </div>
                    </div> <!-- col-xs-12 ends -->

                </div> <!-- my_account_sec ends -->

            </div> <!-- col-xs-12 ends -->
        </div> <!-- row ends -->
    </div> <!-- container ends -->

</section> <!-- <section> ends -->


@stop

