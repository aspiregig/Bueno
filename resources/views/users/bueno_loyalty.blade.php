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
                    <div class="col-xs-12 account_sec my_account_sec loyalty_sec">

                        <section class="title_sec white-bg dashed_border col-xs-12 no-padding">
                            <div class="main-sec col-xs-12">
                                <div class="col-sm-12 col-md-8 left-sec">
                                    <h2 class="style_header_loud">Bueno Loyalty</h2>
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
                            <div class="well col-xs-12 padding-md no-margin">
                                <h4 class="normal_header text-uppercase">Bueno Credits<strong class="paddingleft-md">{{ auth()->user()->points ? auth()->user()->points : 0 }}  @if(auth()->user()->membership->min!=0)-  {{ auth()->user()->membership->name }} @endif</strong></h4>
                            </div> <!-- well ends -->
                        </div> <!-- col-xs-12 ends -->

                        <div class="plans col-xs-12">

                            <div class="refer col-xs-12 col-md-5 @if(!auth()->user()->confirmedOrders()->count() > 0){{ "disabled" }}@endif">
                                <div class="well text-center">
                                    <h4>Refer a friend</h4>
                                </div> <!-- well ends -->

                                <form action="{{ route('users.loyalty.post') }}" method="POST" class="bueno_form text-center">
                                    {{ csrf_field() }}
                                    <h4 class="normal_header marginbottom-md">Refer Bueno to a friend and earn credits !</h4>
                                    <p>Share the coupon code</p>
                                    <div class="border-block black dashed">
                                        <strong class="text-uppercase">
                                            @if(auth()->user()->confirmedOrders()->count() > 0)
                                                {{ auth()->user()->referral_code }}
                                            @else
                                                XXXXXXXX
                                            @endif
                                            </strong>
                                    </div>
                                    <p class="margintb-md">or email them directly<br />
                                        by entering the details below</p>
                                        <p class="margintb-md">
                                            This coupon will be active after your first order.
                                        </p>
                                    <div class="form-group bueno_form_group">
                                        <input type="text" id="fName" required class="bueno_inputtext black dashed full_width" placeholder="Full Name" name="full_name">
                                    </div> <!-- bueno_form_group ends -->
                                    <div class="form-group bueno_form_group">
                                        <input type="text" id="fFName" required class="bueno_inputtext black dashed full_width" name="friend_full_name" placeholder="Friend Full Name">
                                    </div> <!-- bueno_form_group ends -->
                                    <div class="form-group bueno_form_group">
                                        <input type="email" id="femail" required class="bueno_inputtext black dashed full_width" placeholder="Friend's Email Address" name="email">
                                    </div> <!-- bueno_form_group ends -->
                                    <div class="row">
                                        <div class="col-xs-12 marginbottom-sm text-left clearfix">
                                            <a id="messageToggle" href="javascript: void(0)" class="pull-right">edit</a>
                                            <strong>Message from Bueno</strong>
                                        </div> <!-- col-xs-12 ends -->
                                    </div> <!-- row ends -->
                                    <div class="form-group bueno_form_group">
                                        <textarea name="message" required id="message" rows="5" class="bueno_inputtext black dashed full_width disabled">{{ config('bueno.site.referral_email_text')}}</textarea>
                                    </div> <!-- bueno_form_group ends -->
                                    <div class="form-group bueno_form_group">
                                        <input type="submit" class="btn form btn-primary full_width" value="Send">
                                    </div> <!-- bueno_form_group ends -->
                                </form> <!-- bueno_form ends -->
                            </div> <!-- refer ends -->

                            <div class="membership_holder col-xs-12 col-xs-offset-0 col-md-6 col-md-offset-1">
                                @foreach($memberships->filter(function($membership){ return $membership->min > 0; }) as $membership)
                                <div class="membership {{strtolower($membership->name)}} col-xs-12">

                                    <div class="well">
                                        <h4>Bueno {{ucfirst($membership->name)}} Membership</h4>
                                    </div> <!-- well ends -->
                                    <div class="border-block black dashed">
                                        <p class="normal_para">Place {{$membership->min}} orders to become a {{ucfirst($membership->name)}} Member!</p>
                                    </div> <!-- border-block ends -->

                                    <div class="panel-group no-margin" id="{{strtolower($membership->name).'_accordion'}}" role="tablist" aria-multiselectable="true">
                                        <div class="panel dashed panel-default">
                                            <div class="panel-heading" role="tab" id="{{'title'.ucfirst($membership->name)}}">
                                                <h4 class="panel-title">
                                                    <a role="button" class="accordian_link normal arrow_down collapsed" data-toggle="collapse" data-parent="#{{strtolower($membership->name).'_accordion'}}" href="{{'#collapse'.ucfirst($membership->name)}}" aria-expanded="true" aria-controls="{{'collapse'.ucfirst($membership->name)}}" class="full_width">
                                                        Know More
                                                    </a>
                                                </h4>
                                            </div> <!-- panel-heading ends -->
                                            <div id="{{'collapse'.ucfirst($membership->name)}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="titlePlatinum">
                                                <div class="panel-body">
                                                    {{$membership->description}}
                                                </div> <!-- panel-body ends -->
                                            </div> <!-- panel-collapse ends -->
                                        </div> <!-- panel ends -->
                                    </div> <!-- panel-group ends -->

                                </div> <!-- membership platinum ends -->
                                @endforeach

                            </div> <!-- membership_holder ends -->

                        </div> <!-- plans ends -->

                    </div> <!-- my_account_sec ends -->

                </div> <!-- col-xs-12 ends -->
            </div> <!-- row ends -->
        </div> <!-- container ends -->

    </section> <!-- <section> ends -->

@stop