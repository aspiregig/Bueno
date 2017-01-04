@extends('layouts.master')

@section('content')
    @include('partials/static_links_nav_second')

    <section class="static_page_sec catering_enquiry_sec">

        <div class="container more">
            <div class="row">
                <div class="col-xs-12">

                    <div class="well padding-md">
                        <h3 class="normal_header">Catering</h3>
                    </div> <!-- well ends -->

                    <div class="static_content col-xs-12">
                        <div class="main col-xs-12 col-sm-6 col-md-4">
                            <h4>{{ config('bueno.site.catering_text_1') }}</h4>
                            <h4>{{ config('bueno.site.catering_text_2') }}</h4>
                            <div class="border-block black full_width text-center margintop-lg">
                                <label>Call us on:</label><br>
                                <a href="tel:+919811910258">{{ config('bueno.site.catering_phone') }}</a>
                            </div> <!-- border-block ends -->
                            <div class="border-block black full_width text-center">
                                <label>Email us on:</label><br>
                                <a href="mailto:{{ config('bueno.site.catering_email') }}">{{ config('bueno.site.catering_email') }}</a>
                            </div> <!-- border-block ends -->
                            <a href="{{ route('pages.catering.query.get') }}" target="_blank" class="btn btn-xlg btn-primary full_width marginbottom-md">Submit a Query</a>
                            <a href="{{ route('pages.catering.download.get')}}" class="btn btn-xlg btn-primary full_width">Download our Full Menu</a>
                        </div> <!-- main ends -->
                        @include('partials.contact_sidebar')
                    </div> <!-- static_content ends -->

                </div> <!-- col-xs-12 ends -->
            </div> <!-- row ends -->
        </div> <!-- container ends -->

    </section> <!-- <section> ends -->


@stop