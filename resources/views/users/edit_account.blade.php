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
                    
                    <div class="col-xs-12 account_sec my_account_sec">

                        <section class="title_sec white-bg dashed_border col-xs-12 no-padding">
                            <div class="main-sec col-xs-12">
                                <div class="col-sm-12 col-md-8 left-sec">
                                    <h2 class="style_header_loud">My Account</h2>
                                </div> <!-- left-sec ends -->
                                <div class="col-sm-12 col-md-4 right-sec">
                                    <p class="normal_para lines"><small>
                                            @include('partials.user_links')
                                        </small>
                                    </p>
                                </div> <!-- right-sec ends -->
                            </div> <!-- main-sec ends -->
                        </section> <!-- title_sec ends -->

                        <div class="col-xs-12 no-padding">
                            <div id="userDetails" class="well col-xs-12 padding-md no-margin no-padding">
                                <div class="col-xs-10 col-sm-4 no-padding">
                                    <h4 class="normal_header">User Details</h4>
                                </div> <!-- col-xs-4 ends -->
                                <div class="col-xs-2 col-sm-4 no-padding">
                                </div> <!-- col-xs-4 ends -->
                            </div> <!-- well ends -->
                        </div> <!-- col-xs-12 ends -->

                        <form action="{{ route('users.account.edit.post') }}" method="POST" class="bueno_form user_details col-xs-12 margintb-md">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="PATCH">
                            <input type="hidden" name="id" value="{{ auth()->user()->id }}"/>
                            <div class="col-xs-12 no-padding">

                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group bueno_form_group @if ($errors->has('first_name')){{ 'has-error' }}@endif">
                                            @if ($errors->has('first_name'))<span class="help-block">{{ $errors->first('first_name') }} </span>@endif
                                            <input type="text" name="first_name" class="form-control bueno_inputtext black" id="fName" placeholder="First Name" value="{{ old('first_name') ?: auth()->user()->first_name }}" required>
                                        </div> <!-- bueno_form_group ends -->
                                        <div class="form-group bueno_form_group @if ($errors->has('last_name')){{ 'has-error' }}@endif">
                                            @if ($errors->has('last_name'))<span class="help-block">{{ $errors->first('last_name') }} </span>@endif
                                            <input type="text" name="last_name" class="form-control bueno_inputtext black" id="lName" placeholder="Last Name" value="{{ old('last_name') ?: auth()->user()->last_name }}" required>
                                        </div> <!-- bueno_form_group ends -->
                                        <div class="form-group bueno_form_group @if ($errors->has('email')){{ 'has-error' }}@endif">
                                            @if ($errors->has('email'))<span class="help-block">{{ $errors->first('email') }} </span>@endif
                                            <input type="email" name="email" class="form-control bueno_inputtext black" id="emailAddress" placeholder="Email" value="{{ old('email') ?: auth()->user()->email }}" required>
                                        </div> <!-- bueno_form_group ends -->
                                        <div class="form-group bueno_form_group @if ($errors->has('phone')){{ 'has-error' }}@endif">
                                            @if ($errors->has('phone'))<span class="help-block">{{ $errors->first('phone') }} </span>@endif
                                            <input type="number" name="phone" class="form-control bueno_inputtext black" id="mobileNo" placeholder="Mobile No." value="{{ old('phone') ?: auth()->user()->phone }}" minlength="10" required>
                                        </div> <!-- bueno_form_group ends -->
                                        <div class="form-group bueno_form_group" @if ($errors->has('date_of_birth')){{ 'has-error' }}@endif">
                                            @if ($errors->has('date_of_birth'))<span class="help-block">{{ $errors->first('date_of_birth') }} </span>@endif
                                            <input type="text" id="date_of_birth" name="date_of_birth" class="form-control bueno_inputtext black"  placeholder="Date of Birth (optional)" value="@if(auth()->user()->date_of_birth != 0000-00-00){{ auth()->user()->date_of_birth }}@endif" required>
                                        </div> <!-- bueno_form_group ends -->
                                        <div class="form-group bueno_form_group">
                                            <label class="bueno_select no_caret form">
                                                <select name="gender" id="gender" class="full_width no-marginbottom bueno_select2">
                                                    <option value="" disabled selected>Gender</option>
                                                    @foreach(config('bueno.gender') as $key => $gender)
                                                    <option value="{{ $key }}" @if((old('gender') ? old('gender') : auth()->user()->gender) == $key){{ "selected" }}@endif>{{ $gender }}</option>
                                                    @endforeach
                                                </select>
                                            </label> <!-- bueno_select ends -->
                                        </div> <!-- bueno_form_group ends -->
                                        <div class="form-group bueno_form_group">
                                           
                                        </div> <!-- bueno_form_group ends -->
                                    </div> <!-- col-xs-12 ends -->
                                </div> <!-- row ends -->

                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-4 account_edit_checkbox">
                                        <div class="checkbox">
                                                <label class="" for="email_notify">
                                                    <input id="email_notify" type="checkbox" name="email_notify" class="inputcheckbox " value="1" @if(auth()->user()->email_notify) checked="" @endif>
                                                    <span class="check_style"></span>
                                                    <span class="txt">Email me Promotional/Offers Notification</span>

                                                </label>
                                            </div> <!-- checkbox ends -->
                                             <div class="checkbox">
                                                <label class="" for="sms_notify">
                                                    <input id="sms_notify" type="checkbox" name="sms_notify" class="inputcheckbox " value="1" @if(auth()->user()->sms_notify) checked="" @endif>
                                                    <span class="check_style"></span>
                                                    <span class="txt">SMS me Promotional/Offers Notification</span>

                                                </label>
                                            </div> <!-- checkbox ends -->
                                            <div class="clearfix"></div>
                                    </div> <!-- col-xs-12 ends -->
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group bueno_form_group">
                                            <input type="submit" class="btn form btn-primary full_width" value="Save Details">
                                        </div> <!-- bueno_form_group ends -->
                                    </div> <!-- col-xs-12 ends -->
                                </div> <!-- row ends -->

                        </form> <!--  user_details form ends -->

                        <div class="col-xs-12 no-padding">
                            <div id="userDetails" class="well col-xs-12 padding-md no-margin no-padding">
                                <div class="col-xs-10 col-sm-4 no-padding">
                                    <h4 class="normal_header">Update Password</h4>
                                </div> <!-- col-xs-4 ends -->
                                <div class="col-xs-2 col-sm-4 no-padding">
                                </div> <!-- col-xs-4 ends -->
                            </div> <!-- well ends -->
                        </div> <!-- col-xs-12 ends -->

                        <form action="{{ route('users.account.password.post') }}" method="POST" class="bueno_form user_details col-xs-12 margintb-md">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="PATCH">
                            <input type="hidden" name="id" value="{{ auth()->user()->id }}"/>
                            <div class="col-xs-12 no-padding">

                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                         <div class="form-group bueno_form_group @if ($errors->has('old_password')){{ 'has-error' }}@endif">
                                            @if ($errors->has('phone'))<span class="help-block">{{ $errors->first('old_password') }} </span>@endif
                                            <input type="password" name="old_password" class="form-control bueno_inputtext black" id="mobileNo" placeholder="Old Password" required>
                                        </div> <!-- bueno_form_group ends -->
                                        <div class="form-group bueno_form_group @if ($errors->has('new_password')){{ 'has-error' }}@endif">
                                            @if ($errors->has('phone'))<span class="help-block">{{ $errors->first('new_password') }} </span>@endif
                                            <input type="password" name="new_password" class="form-control bueno_inputtext black" id="mobileNo" placeholder="New Passsword"  minlength="6" required>
                                        </div> <!-- bueno_form_group ends -->

                                    </div> <!-- bueno_form_group ends -->
                                </div> <!-- col-xs-12 ends -->

                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                         <div class="form-group bueno_form_group @if ($errors->has('new_password_confirm')){{ 'has-error' }}@endif">
                                            @if ($errors->has('phone'))<span class="help-block">{{ $errors->first('new_password_confirm') }} </span>@endif
                                            <input type="password" name="new_password_confirm" class="form-control bueno_inputtext black" id="mobileNo" placeholder="Confirm Password Passsword" minlength="6" required>
                                        </div> <!-- bueno_form_group ends -->
                                    </div> <!-- col-xs-12 ends -->
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group bueno_form_group">
                                            <input type="submit" class="btn form btn-primary full_width" value="Save Details">
                                        </div> <!-- bueno_form_group ends -->
                                    </div> <!-- col-xs-12 ends -->
                                </div> <!-- row ends -->

                        </form> <!--  user_details form ends -->
                    </div> <!-- col-xs-12 ends -->



                        <div class="bottom_action_bar col-xs-12">
                            <div class="first col-xs-12 col-sm-6 col-md-4">
                                <a id="addAddressForm" href="{{ route('users.address.new.get') }}" class="btn btn-xlg btn-primary-outline full_width">+ Add Address</a>
                            </div> <!-- first ends -->
                            <div class="second col-xs-12 col-xs-offset-0 col-sm-6 col-sm-offset-0 col-md-4 col-md-offset-4">

                            </div> <!-- second ends -->
                        </div> <!-- bottom_action_bar ends -->

                    </div> <!-- my_account_sec ends -->

                </div> <!-- col-xs-12 ends -->
            </div> <!-- row ends -->
        </div> <!-- container ends -->

    </section> <!-- <section> ends -->



@endsection