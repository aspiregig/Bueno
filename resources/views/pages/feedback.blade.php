@extends('layouts.master')

@section('content')
   @include('partials/static_links_nav_second')

    <section class="static_page_sec feedback_sec">

        <div class="container more">
            <div class="row">
                <div class="col-xs-12">

                    <div class="well padding-md">
                        <h3 class="normal_header">Feedback</h3>
                    </div> <!-- well ends -->

                    <div class="static_content col-xs-12">
                        <div class="main col-xs-12 col-sm-6 col-md-5">
                            <h3 class="marginbottom-md paddingleft-sm"><em>We would love to hear from you!</em></h3>
                            <form action="" class="bueno_form">
                                <div class="form-group bueno_form_group has-error">
                                    <span class="help-block">Please enter your first name</span>
                                    <input type="text" class="bueno_inputtext black full_width" id="fName" placeholder="First Name">
                                </div> <!-- bueno_form_group ends -->
                                <div class="form-group bueno_form_group has-error">
                                    <span class="help-block">Please enter your last name</span>
                                    <input type="text" class="bueno_inputtext black full_width" id="lName" placeholder="Last Name">
                                </div> <!-- bueno_form_group ends -->
                                <div class="form-group bueno_form_group has-error">
                                    <span class="help-block">Please enter your email id</span>
                                    <input type="email" class="bueno_inputtext black full_width" id="email" placeholder="Email Address">
                                </div> <!-- bueno_form_group ends -->
                                <div class="form-group bueno_form_group has-error">
                                    <span class="help-block">Please enter your mobile number</span>
                                    <input type="number" class="bueno_inputtext black full_width" id="mobileNo" placeholder="Mobile No.">
                                </div> <!-- bueno_form_group ends -->
                                <div class="form-group bueno_form_group has-error">
                                    <span class="help-block">Please enter a valid subject</span>
                                    <input type="text" class="bueno_inputtext black full_width" id="subject" placeholder="Subject">
                                </div> <!-- bueno_form_group ends -->
                                <div class="form-group bueno_form_group has-error">
                                    <span class="help-block">Please enter your feedback</span>
                                    <label for="message" class="label_inputtext">Feedback (upto 400 words)</label>
                                    <textarea class="bueno_inputtext black full_width" id="message" rows="6"></textarea>
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