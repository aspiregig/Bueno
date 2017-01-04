@extends('layouts.master')

@section('content')

    <section class="paddingbottom-xlg">

        <div class="container more">
            <div class="row">
                <div class="col-xs-12">

                    @include('partials.flash')

                    <div class="col-xs-12 col-md-5 account_sec signin_sec">

                        <section class="title_sec white-bg dashed_border col-xs-12 no-padding">
                            <div class="main-sec stick_lines col-xs-12">
                                <div class="col-xs-12">
                                    <h2 class="style_header_loud">Login</h2>
                                </div> <!-- left-sec ends -->
                            </div> <!-- col-xs-12 ends -->
                        </section> <!-- title_sec ends -->

                        <form action="{{ route('users.login.post') }}" method="POST" class="bueno_form col-xs-12 margintop-md">
                            {{ csrf_field() }}
                            <div class="form-group bueno_form_group">
                                <input type="number" class="form-control bueno_inputtext black" required id="emailAddress" name="login_phone" placeholder="Phone Number" value="{{ old('login_phone') }}">
                            </div> <!-- bueno_form_group ends -->
                            <div class="form-group bueno_form_group">
                                <input type="password" class="form-control bueno_inputtext black" required id="login_password" name="login_password" placeholder="Password">
                            </div> <!-- bueno_form_group ends -->
                            <input type="submit" class="btn btn-xlg btn-primary full_width" value="Login">
                            <a href="{{ route('users.forgot_password.get') }}" class="btn btn-xlg btn-secondary full_width text-capitalize margintop-sm">Forgot Your Password?</a>
                        </form> <!-- bueno_form ends -->

                    </div> <!-- signin_sec ends -->

                </div> <!-- col-xs-12 ends -->
            </div> <!-- row ends -->
        </div> <!-- container ends -->

    </section> <!-- catering_query ends -->



@endsection