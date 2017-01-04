@extends('layouts.master')

@section('content')


    <!-- ############################## -->
    <!-- ############ BODY ############ -->
    <!-- ############################## -->

@if(auth()->user()->mobile_verify != 1)
    <section class="title_sec gray-dim-bg">
        <div class="container more">
            <div class="row">
                <div class="col-xs-12">

                    <div class="main-sec">
                        Please verify your phone number. <a href="{{ route('users.verify.get') }}"><strong>Click here</strong></a> to verify your phone number.
                    </div> <!-- main-sec ends -->

                </div> <!-- col-xs-12 ends -->
            </div> <!-- row ends -->
        </div> <!-- container ends -->
    </section> <!-- title_sec ends -->
@endif



    <section class="paddingbottom-xlg marginbottom-xlg">

        <div class="container more">
            <div class="row">
                <div class="col-xs-12">
                    @include('partials.flash')
                    @include('partials.errors')
                    <div class="col-xs-12 account_sec my_account_sec">

                        <section class="title_sec white-bg dashed_border col-xs-12 no-padding">
                            <div class="main-sec col-xs-12">
                                <div class="col-sm-12 col-md-8 left-sec">
                                    <h2 class="style_header_loud">My Account</h2>
                                </div> <!-- left-sec ends -->
                                <div class="col-sm-12 col-md-4 right-sec">
                                    <p class="normal_para lines">
                                        <small>
                                            @include('partials.user_links')
                                        </small>
                                    </p>
                                </div> <!-- right-sec ends -->
                            </div> <!-- main-sec ends -->
                        </section> <!-- title_sec ends -->

                        <div class="col-xs-12 no-padding">
                            <div class="well col-xs-12 padding-md no-margin no-padding">
                                <div class="col-xs-10 col-sm-4 no-padding">
                                    <h4 class="normal_header">User Details</h4>
                                </div> <!-- col-xs-4 ends -->
                                <div class="col-xs-2 col-sm-4 no-padding">
                                    <a href="{{ route('users.account.edit.post') }}">edit</a>
                                </div> <!-- col-xs-4 ends -->
                            </div> <!-- well ends -->
                        </div> <!-- col-xs-12 ends -->

                        <form action="" class="bueno_form user_details col-xs-12 margintb-md">
                            <div class="col-xs-12 no-padding">

                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <p class="form_txt">{{ auth()->user()->full_name }}</p>
                                        <p class="form_txt">{{ auth()->user()->email }}</p>
                                        <p class="form_txt">{{ auth()->user()->phone }}</p>
                                        @if(auth()->user()->date_of_birth != '0000-00-00')
                                            <p class="form_txt">{{ auth()->user()->date_of_birth }}</p>
                                        @endif
                                        @if(auth()->user()->gender)
                                            <p class="form_txt">{{ config('bueno.gender')[auth()->user()->gender] }}</p>
                                        @endif
                                    </div> <!-- col-xs-12 ends -->
                                </div> <!-- row ends -->

                            </div> <!-- col-xs-12 ends -->
                        </form> <!--  user_details form ends -->

                        @foreach(auth()->user()->addresses as $address)
                        <div class="col-xs-12 no-padding">
                            <div class="well col-xs-12 padding-md no-margin no-padding">
                                <div class="col-xs-10 col-sm-4 no-padding">
                                    <h4 class="normal_header">{{ $address->address_name }} Address</h4>
                                </div> <!-- col-xs-4 ends -->
                                <div class="col-xs-2 col-sm-4 no-padding">
                                    <a href="{{ route('users.address.edit.get', $address->id) }}">edit</a>
                                </div> <!-- col-xs-4 ends -->
                            </div> <!-- well ends -->
                        </div> <!-- col-xs-12 ends -->

                        <form action="" class="bueno_form billing_address col-xs-12 margintb-md">
                            <div class="col-xs-12 no-padding">

                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <p class="form_txt text-uppercase">{{ $address->address_name }}</p>
                                        <p class="form_txt">{{ $address->address }}</p>
                                        <p class="form_txt">{{ $address->area->name }}</p>
                                        <p class="form_txt">{{ $address->pincode }}</p>
                                    </div> <!-- col-xs-12 ends -->
                                </div> <!-- row ends -->

                            </div> <!-- col-xs-12 ends -->
                        </form> <!--  billing_address form ends -->

                        @endforeach


                        <div class="bottom_action_bar col-xs-12">
                            <div class="first col-xs-12 col-sm-6 col-md-4">
                                <a href="{{ route('users.address.new.get') }}" class="btn btn-xlg btn-primary-outline full_width">+ Add Address</a>
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