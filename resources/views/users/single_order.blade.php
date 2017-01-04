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
                            <table class="table table-bordered cart_table no-marginbottom single_order">
                                <thead>
                                <tr>
                                    <th>Item</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-center">Rate</th>
                                    <th class="text-center">Price</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="text-uppercase">Order Number: {{ $order->order_no }}</td>
                                </tr>
                                @foreach($order->orderItems as $item)
                                <tr>
                                    <td class="product_detail">
                                        <div class="sec_table">
                                            <div class="image sec_table_cell">
                                                <a href="{{ $item->item_url }}">
                                                        <div class="cart_page_item_image" style="background-image: url({{ $item->image_url }})"></div>
                                                </a>
                                            </div> <!-- image ends -->
                                            <div class="details sec_table_cell">
                                                <div class="details_holder">
                                                        <h4 class="title"><a href="{{ $item->item_url }}">{{ $item->itemable->name }}</a></h4>
                                                </div> <!-- details_holder ends -->
                                            </div> <!-- details ends -->
                                        </div> <!-- sec_table ends -->
                                    </td> <!-- product_detail ends -->
                                    <td class="product_quantity">
                                        <p>{{ $item->pivot->quantity }}</p>
                                    </td> <!-- product_quantity ends -->
                                    <td class="product_rate">
                                        <span class="price">₹ {{ $item->pivot->unit_price }}</span>
                                    </td> <!-- product_rate ends -->
                                    <td class="product_price">
                                        <span class="price">₹ {{ $item->pivot->unit_price * $item->pivot->quantity }}</span>
                                    </td> <!-- product_price ends -->
                                </tr>
                                @endforeach
                                @if($order->coupon || $order->invoice->where('charge_for','VAT')->first()->amount)
                                    <tr>
                                        <td colspan="2">
                                            @if($order->coupon)
                                                <p class="normal_para"><strong class="text-uppercase">Coupon Applied : {{ $order->coupon->code }}</strong></p>
                                            @endif
                                        </td>
                                        <td class="text-center sec_table_cell">Vat</td>
                                        <td class="text-center sec_table_cell">₹ {{$order->invoice->where('charge_for','VAT')->first()->amount}}</td>
                                    </tr>
                                @endif
                                @if($order->invoice->where('charge_for','Packaging Charge')->first()->amount || $order->ngo)
                                <tr>
                                    <td colspan="2">
                                        @if($order->ngo)
                                            <p class="normal_para">₹{{ $order->ngo->default_donation_amount }} donated towards {{ $order->ngo->name }}</p>
                                        @endif
                                    </td>
                                        <td class="text-center sec_table_cell">Packaging Charge</td>
                                        <td class="text-center sec_table_cell">₹ {{$order->invoice->where('charge_for','Packaging Charge')->first()->amount}}</td>
                                </tr>
                                @endif
                                @if($order->invoice->where('charge_for','Service Charge')->first()->amount)
                                    <tr>
                                        <td colspan="2">

                                        </td>
                                        <td class="text-center sec_table_cell">Service Charge</td>
                                        <td class="text-center sec_table_cell">₹ {{$order->invoice->where('charge_for','Service Charge')->first()->amount}}</td>
                                    </tr>
                                @endif
                                @if($order->invoice->where('charge_for','Points Redeemed')->first()->amount)
                                    <tr>
                                        <td colspan="2">

                                        </td>
                                        <td class="text-center sec_table_cell">Credits Redeemed</td>
                                        <td class="text-center sec_table_cell">₹  -{{$order->invoice->where('charge_for','Points Redeemed')->first()->amount}}</td>
                                    </tr>
                                @endif
                                @if($order->invoice->where('charge_for','Discount')->first()->amount)
                                <tr>
                                    <td colspan="2">

                                    </td>
                                    <td class="text-center sec_table_cell">Discount</td>
                                    <td class="text-center sec_table_cell">₹  -{{$order->invoice->where('charge_for','Discount')->first()->amount}}</td>
                                </tr>
                                @endif
                                @if($order->invoice->where('charge_for','Delivery Charge')->first()->amount)
                                    <tr>
                                        <td colspan="2">

                                        </td>
                                        <td class="text-center sec_table_cell">Delivery Charge</td>
                                        <td class="text-center sec_table_cell">₹  {{ $order->invoice->where('charge_for','Delivery Charge')->first()->amount}}</td>
                                    </tr>
                                @endif
                                @if($order->invoice->where('charge_for','Service Tax')->first()->amount)
                                    <tr>
                                        <td colspan="2">

                                        </td>
                                        <td class="text-center sec_table_cell">Service Tax</td>
                                        <td class="text-center sec_table_cell">₹  {{ $order->invoice->where('charge_for','Service Tax')->first()->amount}}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td class="noborder" colspan="2">
                                    </td>
                                    <td class="text-center sec_table_cell">Total</td>
                                    <td class="text-center sec_table_cell">₹ {{ $order->invoice->where('charge_for','Total Amount')->first()->amount}}</td>
                                </tr>
                                </tbody>
                            </table> <!-- cart_table ends -->
                        </div> <!-- table-responsive ends -->

                        <div class="table-responsive cart_table_responsive marginbottom-lg">
                            <table class="table table-bordered cart_table">
                                <tbody>
                                </tbody>
                            </table> <!-- cart_table ends -->
                        </div> <!-- table-responsive ends -->
                    </div> <!-- col-xs-12 ends -->

                </div> <!-- my_account_sec ends -->

            </div> <!-- col-xs-12 ends -->
        </div> <!-- row ends -->
    </div> <!-- container ends -->

</section> <!-- <section> ends -->




@stop