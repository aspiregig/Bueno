@extends('layouts.master')

@section('content')

    <!-- ############################## -->
    <!-- ############ BODY ############ -->
    <!-- ############################## -->

    <section class="title_sec gray-dim-bg">
        <div class="container more">
            <div class="row">
                <div class="col-xs-12">


                </div> <!-- col-xs-12 ends -->
            </div> <!-- row ends -->
        </div> <!-- container ends -->
    </section> <!-- title_sec ends -->

    <section class="paddingbottom-xlg marginbottom-xlg">

        <div class="container more">
            <div class="row">
                <div class="col-xs-12">

                    @include('partials.flash')

                    <div class="col-xs-12 col-md-10 account_sec forgot_pass_sec">

                        <section class="title_sec white-bg dashed_border col-xs-12 no-padding">
                            <div class="main-sec stick_lines col-xs-12">
                                <div class="col-xs-12">
                                    <h2 class="style_header_loud">Opt Out</h2>
                                </div> <!-- left-sec ends -->
                            </div> <!-- col-xs-12 ends -->
                        </section> <!-- title_sec ends -->

                        <form action="{{ route('pages.otp_out.sms.post') }}" method="POST" class="bueno_form col-xs-12 margintop-md">
                            {{ csrf_field() }}
                            <p>Please enter your mobile number to stop receiving further sms notifications.<br /></p>
                            <div class="col-xs-12 col-sm-8 col-md-4 no-padding">
                                <div class="form-group bueno_form_group">
                                    <input type="number" required class="form-control bueno_inputtext black" id="phoneNumber" name="phone" placeholder="Phone Number">
                                </div> <!-- bueno_form_group ends -->
                                <input type="submit" class="btn btn-xlg btn-primary full_width" value="Submit">
                            </div> <!-- col-sm-4 ends -->
                        </form> <!-- bueno_form ends -->

                    </div> <!-- signup_sec ends -->

                </div> <!-- col-xs-12 ends -->
            </div> <!-- row ends -->
        </div> <!-- container ends -->

    </section> <!-- catering_query ends -->

    @if(request()->has('postRegister'))
        <script>
            fbq('track', 'CompleteRegistration');
        </script>
    @endif


@endsection