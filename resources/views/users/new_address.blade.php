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
                    @include('admin.partials.errors')
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
                            <div id="billingAddress" class="well col-xs-12 padding-md no-margin no-padding">
                                <div class="col-xs-10 col-sm-4 no-padding">
                                    <h4 class="normal_header">New Address</h4>
                                </div> <!-- col-xs-4 ends -->
                                <div class="col-xs-2 col-sm-4 no-padding">
                                </div> <!-- col-xs-4 ends -->
                            </div> <!-- well ends -->
                        </div> <!-- col-xs-12 ends -->

                        <form action="{{  route('users.address.new.post') }}" method="POST" class="bueno_form billing_address col-xs-12 margintb-md">
                            {{ csrf_field() }}
                            <input type="hidden" value="{{ auth()->user()->id }}" name="user_id"/>
                            <div class="col-xs-12 no-padding">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group bueno_form_group">
                                            <input type="text" class="form-control bueno_inputtext black" required name="address_name" id="title" placeholder="Title ( Home, Office , etc )" value="{{ old('address_name') }}">
                                        </div> <!-- bueno_form_group ends -->
                                        <div class="form-group bueno_form_group">
                                            <textarea type="text" class="form-control bueno_inputtext black" required placeholder="Address" name="address">{{ old('address') }}</textarea>
                                        </div> <!-- bueno_form_group ends -->
                                        <div class="form-group bueno_form_group">
                                            <input type="number" class="form-control bueno_inputtext black" required id="pincode" placeholder="Pincode" name="pincode" value="{{ old('pincode') }}">
                                        </div> <!-- bueno_form_group ends -->
                                    </div> <!-- col-xs-12 ends -->
                                </div> <!-- row ends -->

                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group bueno_form_group">
                                            <label class="bueno_select no_caret form">
                                                <select name="area_id" class="full_width no-marginbottom bueno_select2">
                                                    @foreach($areas as $area)
                                                        <option value="{{ $area->id }}" @if($area->id == old('area_id')){{ "selected" }}@endif>{{ $area->name }}</option>
                                                    @endforeach
                                                </select>
                                            </label> <!-- bueno_select ends -->
                                        </div> <!-- bueno_form_group ends -->
                                    </div> <!-- col-xs-12 ends -->
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="form-group bueno_form_group">
                                            <input type="submit" class="btn form btn-primary full_width" value="Save Details">
                                        </div> <!-- bueno_form_group ends -->
                                    </div> <!-- col-xs-12 ends -->
                                </div> <!-- row ends -->

                            </div> <!-- col-xs-12 ends -->
                        </form> <!--  billing_address form ends -->

                </div> <!-- my_account_sec ends -->

            </div> <!-- col-xs-12 ends -->
        </div> <!-- row ends -->
        </div> <!-- container ends -->

    </section> <!-- <section> ends -->



@endsection