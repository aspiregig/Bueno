@extends('layouts.master')

@section('content')

    @include('partials/static_links_nav_second')

    <section class="static_page_sec contact_us_sec">

        <div class="container more">
            <div class="row">
                <div class="col-xs-12">
                    <div class="well padding-md">
                        <h3 class="normal_header">Enquires</h3>
                    </div> <!-- well ends -->

                    <div class="static_content col-xs-12">
                        <div class="main col-xs-12 col-sm-6 col-md-5">

                            @include('partials.flash')

                            <h3 class="marginbottom-md paddingleft-sm"><em>For Business Query</em></h3>
                            <form action="{{ route('pages.business.post') }}" class="bueno_form" method="POST">
                                {{ csrf_field() }}
                                <div class="form-group bueno_form_group @if($errors->has('full_name')){{ 'has-error' }}@endif">
                                    @if ($errors->has('full_name'))<span class="help-block">{{ $errors->first('full_name') }} </span>@endif
                                    <input type="text" name="full_name" required class="bueno_inputtext black full_width" id="fName" placeholder="Full Name" value="{{ old('full_name') }}">
                                </div> <!-- bueno_form_group ends -->
                                <div class="form-group bueno_form_group @if($errors->has('email')){{ 'has-error' }}@endif">
                                    @if ($errors->has('email'))<span class="help-block">{{ $errors->first('email') }} </span>@endif
                                    <input type="email" name="email" required class="bueno_inputtext black full_width" id="email" placeholder="Email Address" value="{{ old('email') }}">
                                </div> <!-- bueno_form_group ends -->
                                <div class="form-group bueno_form_group @if($errors->has('phone')){{ 'has-error' }}@endif">
                                    @if ($errors->has('phone'))<span class="help-block">{{ $errors->first('phone') }} </span>@endif
                                    <input type="number" name="phone" required class="bueno_inputtext black full_width" id="mobileNo" placeholder="Mobile No." value="{{ old('phone') }}">
                                </div> <!-- bueno_form_group ends -->
                                <div class="form-group bueno_form_group @if($errors->has('subject')){{ 'has-error' }}@endif">
                                    @if ($errors->has('subject'))<span class="help-block">{{ $errors->first('subject') }} </span>@endif
                                    <input type="text" name="subject" requried class="bueno_inputtext black full_width" id="subject" placeholder="Subject" value="{{ old('subject') }}">
                                </div> <!-- bueno_form_group ends -->
                                <div class="form-group bueno_form_group @if($errors->has('query')){{ 'has-error' }}@endif">
                                    @if ($errors->has('query'))<span class="help-block">{{ $errors->first('query') }} </span>@endif
                                    <label for="message" class="label_inputtext">Message (upto 400 words)</label>
                                    <textarea class="bueno_inputtext black full_width" name="query" id="message" rows="6">{{ old('query') }}</textarea>
                                </div> <!-- bueno_form_group ends -->
                                <div class="form-group bueno_form_group">
                                    <input type="submit" class="btn btn-primary full_width">
                                </div> <!-- bueno_form_group ends -->
                            </form> <!-- bueno_form ends -->
                        </div> <!-- main ends -->
                        @include('partials.contact_sidebar')
                    </div> <!-- static_content ends -->

                </div> <!-- col-xs-12 ends -->
            </div> <!-- row ends -->
        </div> <!-- container ends -->

    </section> <!-- <section> ends -->

@stop