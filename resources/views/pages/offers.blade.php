@extends('layouts.master')

@section('content')

    @include('partials.locality_select')

    <section class="hot_deals_sec hot_deals_listing_sec">

        <section class="title_sec white-bg">
            <div class="container more">
                <div class="row">
                    <div class="main-sec stick_lines col-xs-12">
                        <div class="col-sm-12 col-md-6 left-sec">
                            <h2 class="style_header_loud">Offers</h2>
                        </div> <!-- left-sec ends -->
                        <div class="col-sm-12 col-md-6 right-sec">
                            <p class="normal_para lines"><small>
                                    <em>Limited Period</em>
                                    <em>Value for Money</em>
                                    <em>Easy Ordering</em>
                                    <em>Real Time Delivery Tracking</em></small>
                            </p>
                        </div> <!-- right-sec ends -->
                    </div> <!-- col-xs-12 ends -->
                    <div class="">
                        <div class="breadcrumb_holder col-xs-12">
                            {{--{!! $breadcrumbs !!}--}}
                        </div> <!-- breadcrumb_holder ends -->
                    </div>
                </div> <!-- row ends -->
            </div> <!-- container ends -->
        </section> <!-- title_sec ends -->

        <div class="container more">
            <div class="row">
                <div class="col-xs-12">

                    <!-- Listing Items -->
                    <div class="col-xs-12 listing_grid listing_deals marginbottom-md no-padding " id="hot_deals_page_container">
                        @foreach($coupons as $coupon)
                            <div class="listing_padding col-xs-12 col-sm-12 col-md-12">
                                <div class="listing_item coupon_item">
                                    <div class="row">
                                        {{--<div class="col-md-2">--}}
                                            {{--<div class="coupon_giveaway">--}}
                                                {{--{{ $coupon->offer_text }}--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        <div class="col-md-9">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="coupon_code">
                                                        <h4 class="title">{{ $coupon->offer_text }}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="details coupon-short-text">
                                                <p class="desc text-muted">{{ $coupon->description  }}</p>
                                            </div> <!-- details ends -->
                                        </div>
                                        <div class="col-md-3">
                                            <div class="copy-coupon">
                                                <button class="clipboard" data-clipboard-text="{{ $coupon->code }}">
                                                    <h4>{{ $coupon->code }}</h4>
                                                    <em>Click to copy code</em>
                                                </button>
                                            </div>
                                        </div>
                                    </div>


                                </div> <!-- listing_item ends -->
                            </div> <!-- col-xs-12 ends -->
                        @endforeach
                            @if($coupons->count() == 0)
                                <div class="col-xs-12 placeholder_message">
                                    <h4 class="normal_header"><i class="ion-android-alert paddingright-xs"></i>Sorry, there are no results to show.</h4>
                                </div> <!-- placeholder_message ends -->
                            @endif
                    </div> <!-- listing_deals ends -->
                </div> <!-- col-xs-12 ends -->
            </div> <!-- row ends -->
        </div> <!-- container ends -->

    </section> <!-- hot_deals_listing_sec ends -->



@endsection