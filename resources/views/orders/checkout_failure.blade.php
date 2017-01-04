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

                    <div class="col-xs-12 account_sec my_account_sec">

                        <section class="title_sec white-bg dashed_border col-xs-12 no-padding">
                            <div class="main-sec stick_lines col-xs-12">
                                <div class="col-sm-12 left-sec">
                                    <h2 class="style_header_loud">Order Unsuccessful!</h2>
                                </div> <!-- left-sec ends -->
                                <div class="col-sm-12 col-md-4 right-sec">
                                </div> <!-- right-sec ends -->
                            </div> <!-- main-sec ends -->
                        </section> <!-- title_sec ends -->

                        <div class="content col-xs-12 margintop-lg">
                            <p>There was some error in checkout. <br>
                                Please try again.
                            </p>
                            <a href="{{ route('pages.index') }}" class="btn btn-primary margintop-md">Continue browsing</a>
                        </div> <!-- content ends -->

                    </div> <!-- my_account_sec ends -->

                </div> <!-- col-xs-12 ends -->
            </div> <!-- row ends -->
        </div> <!-- container ends -->

    </section> <!-- <section> ends -->

@stop