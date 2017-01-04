@extends('layouts.master')

@section('content')
    @include('partials/static_links_nav_second')

    <section class="static_page_sec careers_sec">

        <div class="container more">
            <div class="row">
                <div class="col-xs-12">

                    <div class="well padding-md">
                        <h3 class="normal_header">Careers</h3>
                    </div> <!-- well ends -->

                    <div class="static_content col-xs-12">
                        <div class="main col-xs-12 col-sm-6 col-md-5">
                            <h4 class="margintop-sm">We are constantly looking to expand our team. Join us and become part of an exciting food ecosystem! Email us your CV on</h4>
                            <a class="txt-big" href="mailto:{{ config('bueno.site.careers_email') }}">{{ config('bueno.site.careers_email') }}</a>
                        </div> <!-- main ends -->
                        @include('partials.contact_sidebar')
                    </div> <!-- static_content ends -->

                </div> <!-- col-xs-12 ends -->
            </div> <!-- row ends -->
        </div> <!-- container ends -->

    </section> <!-- <section> ends -->



@stop