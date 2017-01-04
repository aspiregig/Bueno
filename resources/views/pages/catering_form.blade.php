@extends('layouts.master')

@section('content')
    <section class="title_sec gray-dim-bg">
        <div class="container more">
            <div class="row">
                <div class="col-xs-12">

                    <div class="main-sec">
                        <p class="no-margin">Minimum order value &#8377; 5,000/- (exclusive of delivery, crockery, cutlery &amp; on site support)
                            <br />
                            Please order 24 hours in advance</p>
                    </div> <!-- main-sec ends -->

                </div> <!-- col-xs-12 ends -->
            </div> <!-- row ends -->
        </div> <!-- container ends -->
    </section> <!-- title_sec ends -->

    <section class="catering_sec catering_query hot_deals_listing_sec">

        <section class="title_sec white-bg">
            <div class="container more">
                <div class="row">
                    <div class="main-sec stick_lines col-xs-12">
                        <div class="col-sm-12 col-md-8 left-sec">
                            <h2 class="style_header_loud">Catering Menu</h2>
                        </div> <!-- left-sec ends -->
                        <div class="col-sm-12 col-md-4 right-sec">
                            <p class="normal_para lines"><small>
                                    <em>Global Cuisine</em>
                                    <em>5-Star Chefs</em>
                                    <em>Completely customizable</em></small>
                            </p>
                        </div> <!-- right-sec ends -->
                    </div> <!-- col-xs-12 ends -->
                    <div class="breadcrumb_holder col-xs-12">

                    </div> <!-- breadcrumb_holder ends -->
                </div> <!-- row ends -->
            </div> <!-- container ends -->
        </section> <!-- title_sec ends -->

        <div class="container more">
            <div class="row">
                <div class="col-xs-12">

                    <section class="highlight_active_sec catering_menu_sec">
                        <div class="container more">
                            <div class="row">
                                <div class="col-xs-12">

                                    @include('partials.flash')
                                    <div class="catering_menu col-xs-12 no-padding">

                                        <div class="catering_menu_left col-xs-12 col-sm-6 col-md-4 left-sec no-padding">
                                            <div class="well">
                                                <h4 class="normal_header">Submit a Query</h4>
                                            </div>
                                            <form action="{{ route('pages.catering.query.post') }}" method="POST" class="bueno_form">
                                                {{ csrf_field() }}
                                                <div class="form-group bueno_form_group @if($errors->has('full_name')){{ 'has-error' }}@endif">
                                                    @if ($errors->has('full_name'))<span class="help-block">{{ $errors->first('full_name') }} </span>@endif
                                                    <input type="text" class="form-control bueno_inputtext black" name="full_name" id="fName" placeholder="Full Name" value="{{ old('full_name') }}" required>
                                                </div> <!-- bueno_form_group ends -->
                                                <div class="form-group bueno_form_group @if($errors->has('email')){{ 'has-error' }}@endif">
                                                    @if ($errors->has('email'))<span class="help-block">{{ $errors->first('email') }} </span>@endif
                                                    <input type="email" class="form-control bueno_inputtext black" name="email" id="lName" placeholder="Email" value="{{ old('email') }}" required>
                                                </div> <!-- bueno_form_group ends -->
                                                <div class="form-group bueno_form_group @if($errors->has('phone')){{ 'has-error' }}@endif">
                                                    @if ($errors->has('phone'))<span class="help-block">{{ $errors->first('phone') }} </span>@endif
                                                    <input type="number" class="form-control bueno_inputtext black" id="phoneNo" name="phone" placeholder="Phone" value="{{ old('phone') }}" required>
                                                </div> <!-- bueno_form_group ends -->
                                                <div class="form-group bueno_form_group @if($errors->has('query')){{ 'has-error' }}@endif">
                                                    @if ($errors->has('query'))<span class="help-block">{{ $errors->first('query') }}</span>@endif
                                                    <label class="label_inputtext" for="query">Query (upto 400 words)</label>
                                                    <textarea id="query" required name="query" class="bueno_inputtext black full_width" cols="30" rows="10">{{ old('query') }}</textarea>
                                                </div> <!-- bueno_form_group ends -->
                                                <input type="submit" class="btn btn-primary full_width" value="Submit">
                                            </form> <!-- bueno_form ends -->
                                        </div> <!-- catering_menu_left ends -->

                                        <div class="catering_menu_right col-xs-12 col-sm-6 right-sec">
                                            <div class="col-xs-12 col-xs-offset-0 col-sm-10 col-sm-offset-1 col-md-6 col-md-offset-0 no-padding">
                                                <div class="border-block blurred full_width text-left">
                                                    <label>Call us on:</label>
                                                    <strong>{{ config('bueno.site.catering_phone') }}</strong>
                                                </div> <!-- border-block ends -->
                                                <div class="border-block blurred full_width text-left marginbottom-md">
                                                    <label>Email us on:</label>
                                                    <strong>{{ config('bueno.site.catering_email') }}</strong>
                                                </div> <!-- border-block ends -->
                                                <a href="{{ route('pages.catering.download.get') }}" class="btn btn-primary full_width">Download our full Menu</a>
                                            </div> <!-- col-xs-12 ends -->
                                        </div> <!-- catering_menu_right ends -->

                                    </div> <!-- catering_menu ends -->

                                </div> <!-- col-xs-12 ends -->
                            </div> <!-- row ends -->
                        </div> <!-- container ends -->
                    </section> <!-- catering_menu_sec ends -->

                </div> <!-- col-xs-12 ends -->
            </div> <!-- row ends -->
        </div> <!-- container ends -->

    </section> <!-- catering_query ends -->




    <section class="title_sec">
        <div class="container more">
            <div class="row">
                <div class="main-sec col-xs-12">
                    <div class="col-sm-12 col-md-6 left-sec">
                        <h2 class="style_header">Trending On Bueno</h2>
                    </div> <!-- left-sec ends -->
                    <div class="col-sm-12 col-md-6 right-sec">
                        <p class="normal_para">Orders from our most popular items today before they are gone!</p>
                    </div> <!-- right-sec ends -->
                </div> <!-- col-xs-12 ends -->
            </div> <!-- row ends -->
        </div> <!-- container ends -->
    </section> <!-- title_sec ends -->


{{--     <section class="meal_carousel_sec">
        <div class="container more">
            <div class="row">
                <div class="col-xs-12">

                    <div class="listing_grid">

                        @include('partials/trending_slider')

                    </div> <!-- listing_grid ends -->

                </div> <!-- col-xs-12 ends -->
            </div> <!-- row ends -->
        </div> <!-- container ends -->
    </section> <!-- title_sec ends --> --}}


@stop