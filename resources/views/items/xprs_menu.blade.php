@extends('layouts.master')

@section('content')

    @include('partials.locality_select')

    <section class="xprs_menu_listing_sec">

        <section class="title_sec white-bg">
            <div class="container more">
                <div class="row">
                    <div class="main-sec stick_lines col-xs-12">
                        <div class="col-sm-12 col-md-9 left-sec">
                            <h2 class="style_header_loud">Global Menu</h2>
                        </div> <!-- left-sec ends -->
                        <div class="col-sm-12 col-md-3 right-sec">
                            <p class="normal_para lines"><small>
                                    <em>Global Cuisine</em>
                                    <em>5-Star Chefs</em>
                                    <em>Easy Ordering</em>
                                    <em>Real Time Delivery Tracking</em></small>
                            </p>
                        </div> <!-- right-sec ends -->
                    </div> <!-- col-xs-12 ends -->
                    <div class="breadcrumb_holder col-xs-12">
                        {!! $breadcrumbs !!}
                    </div> <!-- breadcrumb_holder ends -->
                </div> <!-- row ends -->
            </div> <!-- container ends -->
        </section> <!-- title_sec ends -->

       @include('partials.xprs_menu')

    </section> <!-- hot_deals_listing_sec ends -->

    @include('templates/xprs_menu_listing')


@endsection