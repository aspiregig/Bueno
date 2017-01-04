@extends('layouts.master')

@section('content')

    <section class="paddingbottom-xlg">

        <div class="container more">
            <div class="row">
                <div class="col-xs-12">

                    @include('partials.flash')

                    <div class="col-xs-12 col-md-5 account_sec signup_sec">

                        <section class="title_sec white-bg dashed_border col-xs-12 no-padding">
                            <div class="main-sec stick_lines col-xs-12">
                                <div class="col-xs-12">
                                    <h2 class="style_header_loud">Sign Up</h2>
                                </div> <!-- left-sec ends -->
                            </div> <!-- col-xs-12 ends -->
                        </section> <!-- title_sec ends -->

                        <form action="{{ route('users.register.post') }}" method="POST" class="bueno_form col-xs-12 margintop-md">
                            {{ csrf_field() }}
                            <div class="form-group bueno_form_group @if ($errors->has('last_name')){{ 'has-error' }}@endif">
                                @if ($errors->has('first_name'))<span class="help-block">{{ $errors->first('first_name') }} </span>@endif
                                <input type="text" class="form-control bueno_inputtext black" id="fName" name="first_name" placeholder="First Name" required value="@if(session('user.first_name')){{ session('user.first_name') }}@else{{ old('first_name') }}@endif">
                            </div> <!-- bueno_form_group ends -->
                            <div class="form-group bueno_form_group @if ($errors->has('last_name')){{ 'has-error' }}@endif">
                                @if ($errors->has('last_name'))<span class="help-block">{{ $errors->first('last_name') }} </span>@endif
                                <input type="text" class="form-control bueno_inputtext black" id="lName" name="last_name" placeholder="Last Name" value="@if(session('user.last_name')){{ session('user.last_name') }}@else{{ old('last_name') }}@endif">
                            </div> <!-- bueno_form_group ends -->
                            <div class="form-group bueno_form_group @if ($errors->has('phone')){{ 'has-error' }}@endif">
                                @if ($errors->has('phone'))<span class="help-block">{{ $errors->first('phone') }} </span>@endif
                                <input type="number" class="form-control bueno_inputtext black" id="mobileNo" name="phone" placeholder="Mobile No." required value="{{ old('phone') }}">
                            </div> <!-- bueno_form_group ends -->
                            <div class="form-group bueno_form_group @if ($errors->has('email')){{ 'has-error' }}@endif">
                                @if ($errors->has('email'))<span class="help-block">{{ $errors->first('email') }} </span>@endif
                                <input type="email" class="form-control bueno_inputtext black" id="emailAddress" name="email" placeholder="Email" value="@if(session('user.email')){{ session('user.email') }}@else{{ old('email') }}@endif">
                            </div> <!-- bueno_form_group ends -->
                            <div class="form-group bueno_form_group @if ($errors->has('password')){{ 'has-error' }}@endif">
                                @if ($errors->has('password'))<span class="help-block">{{ $errors->first('password') }} </span>@endif
                                <input type="password" class="form-control bueno_inputtext black" id="password" name="password" required placeholder="Password" >
                            </div> <!-- bueno_form_group ends -->
                            <div class="form-group bueno_form_group @if ($errors->has('confirm_password')){{ 'has-error' }}@endif">
                                @if ($errors->has('confirm_password'))<span class="help-block">{{ $errors->first('confirm_password') }} </span>@endif
                                <input type="password" class="form-control bueno_inputtext black" id="password" name="confirm_password" required placeholder="Confirm Password">
                            </div> <!-- bueno_form_group ends -->
                            <p class="agree-text"><em>I agree to <a href="{{ route('pages.terms_conditions.get') }}">terms and conditions</a></em></p>
                            <input type="submit" class="btn btn-xlg btn-primary full_width" value="Sign Up">
                            <a href="{{ route('users.login.social', 'google') }}" class="btn btn-xlg btn-pink btn-icon icon-left signup_with_gplus full_width margintop-sm">
                                Sign Up using Google+
                                <i class="ion-social-googleplus"></i>
                            </a>
                            <input type="hidden" name="avatar" value="@if(session('user.avatar')){{ session('user.avatar') }}@else{{ old('avatar') }}@endif"/>
                            <a href="{{ route('users.login.social', 'facebook') }}" class="btn btn-xlg btn-pink btn-icon icon-left signup_with_fb full_width margintop-sm">
                                Sign Up using Facebook
                                <i class="ion-social-facebook"></i>
                            </a>
                        </form> <!-- bueno_form ends -->

                    </div> <!-- signup_sec ends -->


                </div> <!-- col-xs-12 ends -->
            </div> <!-- row ends -->
        </div> <!-- container ends -->

    </section> <!-- catering_query ends -->



@endsection