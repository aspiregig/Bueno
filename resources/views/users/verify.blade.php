@extends('layouts.master')

@section('content')

    <!-- ############################## -->
    <!-- ############ BODY ############ -->
    <!-- ############################## -->

    <section class="title_sec gray-dim-bg">
        <div class="container more">
            <div class="row">
                <div class="col-xs-12">

                    <div class="main-sec">
                        Please verify your phone number. <a href="{{ route('users.resend.get') }}"><strong>Click here</strong></a> to resend the OTP Code.
                    </div> <!-- main-sec ends -->

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
                                    <h2 class="style_header_loud">Verify your phone</h2>
                                </div> <!-- left-sec ends -->
                            </div> <!-- col-xs-12 ends -->
                        </section> <!-- title_sec ends -->

                        <form action="{{ route('users.verify.post') }}" method="POST" class="bueno_form col-xs-12 margintop-md">
                            {{ csrf_field() }}
                            <p>Please enter the code we have sent you on SMS.<br />
                               This is a one time task. You don't have to do this again.</p>
                            <div class="col-xs-12 col-sm-8 col-md-4 no-padding">
                                <div class="form-group bueno_form_group">
                                    <input type="number" required class="form-control bueno_inputtext black" id="phoneNumber" name="code" placeholder="OTP Code">
                                </div> <!-- bueno_form_group ends -->
                                <input type="submit" class="btn btn-xlg btn-primary full_width" value="Verify">
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