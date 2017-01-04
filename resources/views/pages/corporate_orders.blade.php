
@extends('layouts.master')

@section('content')
    <!-- ############################## -->
    <!-- ############ BODY ############ -->
    <!-- ############################## -->

    @include('partials/static_links_nav_second')

    <section class="static_page_sec corporate_orders_sec">

        <div class="container more">
            <div class="row">
                <div class="col-xs-12">

                    <div class="well padding-md">
                        <h3 class="normal_header">Corporate Orders</h3>
                    </div> <!-- well ends -->

                    <div class="static_content col-xs-12">
                        <div class="main col-xs-12 col-sm-6 col-md-4">
                            <h4>We undertake small as well as large scale orders for all corporate events.</h4>
                            <div class="border-block black full_width text-center margintop-lg">
                                <label>Call us on:</label><br>
                                <a href="tel:{{ config('bueno.site.catering_phone') }}">{{ config('bueno.site.catering_phone') }}</a>
                            </div> <!-- border-block ends -->
                            <div class="border-block black full_width text-center">
                                <label>Email us on:</label><br>
                                <a href="mailto:{{ config('bueno.site.catering_email') }}">{{ config('bueno.site.catering_email') }}</a>
                            </div> <!-- border-block ends -->
                            <a href="{{ route('pages.catering.query.get') }}" class="btn btn-xlg btn-primary full_width">Submit a Query</a>
                        </div> <!-- main ends -->
                        @include('partials.contact_sidebar')
                    </div> <!-- static_content ends -->

                </div> <!-- col-xs-12 ends -->
            </div> <!-- row ends -->
        </div> <!-- container ends -->

    </section> <!-- <section> ends -->

    <div class="note">
        <div class="container more">
            <div class="row">
                <div class="col-xs-12">
                    <p class="normal_para"><strong>Minimum order value ₹ 5,000/- </strong><em>(exclusive of delivery, crockery, cutlery &amp; on site support)</em> <br>
                        <strong>Please order 24 hours in advance</strong></p>
                </div> <!-- col-xs-12 ends -->
            </div> <!-- row ends -->
        </div> <!-- container ends -->
    </div> <!-- note ends -->


@stop