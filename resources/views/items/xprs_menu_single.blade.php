@extends('layouts.master')

@section('content')


    <section class="hot_deals_sec hot_deals_listing_sec single_item_page">

        <section class="title_sec white-bg">
            <div class="container more">
                <div class="row">
                    <div class="main-sec stick_lines col-xs-12">
                        <div class="col-sm-12 col-md-6 left-sec">
                            <h2 class="style_header_loud">Global Menu</h2>
                        </div> <!-- left-sec ends -->
                        <div class="col-sm-12 col-md-6 right-sec">
                            <p class="normal_para lines"><small>
                                    <em>Limited Period</em>
                                    <em>Value for Money</em>
                                    <em>Easy Ordering</em>
                                    <em>Real Time Delivery Tracking</em></small>
                            </p>
                        </div> <!-- right-sec ends -->
                    </div> <!-- col-xs-12 ends -->
                    <div class="breadcrumb_holder col-xs-12">
                        {!! $breadcrumbs !!}
                    </div> <!-- breadcrumb_holder ends -->
                </div> <!-- row ends -->
            </div> <!-- container ends -->
        </section> <!-- title_sec ends -->

        <div class="container more">
            <div class="row">

                <div class="display_none">
                    <!-- Hot Deal (Listing Show) Title -->
                    <section class="title_sec deals_title">
                        <div class="row">
                            <div class="main-sec col-xs-12">
                                <div class="col-sm-12 col-md-6 left-sec paddingleft-sm">
                                    <h2 class="normal_header">{{ $item->itemable->name }}</h2>
                                </div> <!-- left-sec ends -->
                                <div class="col-sm-12 col-md-6 right-sec">
                                    <div class="deals_nav">
                                        <a href="" class="btn btn-default-inverse">Previous Deal</a>
                                        <a href="" class="btn btn-default-inverse">Next Deal</a>
                                        <a href="" class="btn btn-primary-inverse">View all Deals</a>
                                    </div> <!-- text-right ends -->
                                </div> <!-- right-sec ends -->
                            </div> <!-- col-xs-12 ends -->
                        </div> <!-- row ends -->
                    </section> <!-- deals_title ends -->
                </div>

                <div class="col-xs-12">

                    <!-- Listing Show -->
                    <div class="listing_show col-xs-12 hot_deal_item">
                        <div class="meal_pic col-xs-12 col-sm-6">
                                <div class="img_holder">
                                    @if($item->itemable->status == 2)
                                        <div class="side_ribbon"><span>{{ config('bueno.item_status')[2] }}</span></div>
                                    @endif
                                     <img class="img-responsive" src="{{ $item->image_url }}" alt="">
                                </div>
                            <ul class="meal_social_share">
                                <li class="hover_facebook"><a class="share-popover" href="https://www.facebook.com/sharer/sharer.php?u={{ route('items.xprs-menu.single.get', $item->itemable->slug ) }}"><i class="ion-social-facebook"></i></a></li>
                                <li class="hover_twitter"><a class="share-popover" href="https://twitter.com/home?status={{ route('items.xprs-menu.single.get', $item->itemable->slung ) }}"><i class="ion-social-twitter"></i></a></li>
                                <li class="hover_pinterest"><a class="share-popover" href="https://pinterest.com/pin/create/button/?url={{ route('items.xprs-menu.single.get', $item->itemable->slung ) }}&media=@if($item->itemable_type == 'App\Models\Meal'){{ route('photo.meals',$item->itemable->display_pic_url) }}@else{{ route('photo.combos',$item->itemable->display_pic_url) }}@endif&description={{ $item->itemable->description }}"><i class="ion-social-pinterest"></i></a></li>
                                <li class="hover_linkedin"><a class="share-popover" href="https://www.linkedin.com/shareArticle?mini=true&url={{ route('items.xprs-menu.single.get', $item->itemable->slug ) }}={{ $item->itemable->name }}&summary={{ $item->itemable->description }}"><i class="ion-social-linkedin"></i></a></li>
                            </ul> <!-- meal_social_share ends -->
                        </div> <!-- meal_pic ends -->
                        <div class="meal_details col-xs-12 col-sm-6">
                            <div class="col-xs-12 col-md-6 no-padding">
                                <header>
                                    <h1 class="title single_meal_title">{{ $item->itemable->name }}</h1>
                                <div class="meal_category @if($item->itemable->type != 1){{"non-veg"}}@else{{"veg"}}@endif"></div>
                                    @if($item->itemable->original_price > $item->itemable->discount_price)
                                        <div class="original_price">
                                            &#8377; <span class="strike-through">{{ $item->itemable->original_price }}</span>
                                        </div>
                                    @endif
                                    <p class="price">&#8377; {{ $item->itemable->discount_price }}/-</p>
                                <p class="normal_para text-muted">{{ $item->itemable->long_description }}</p>
                                    <p class="extras text-muted">Serves: {{ $item->itemable->serves }} @if($item->itemable->spiceLevel && $item->itemable->spiceLevel->name != "")|   Spice: {{ $item->itemable->spiceLevel->name }} @endif</p>
                                </header>
                            </div> <!-- col-xs-12 ends -->
                            <div class="col-xs-12 no-padding">
                                <div class="form-group bueno_form_group has-error clearfix">
                                    <div class="col-xs-12 col-md-6 left_btn_sec">
                                        <p>Quantity</p>
                                        <label class="bueno_select no_caret no-padding">
                                            <select name="" id="locality" class="full_width no-marginbottom bueno_select2 item_quantity_select">
                                                <option value="">Quantity</option>
                                                @foreach(range(1, 10) as $value)
                                                    <option value="{{ $value }}" @if($value == 1) selected @endif>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </label> <!-- bueno_select ends -->
                                    </div> <!-- left_btn_sec ends -->
                                </div> <!-- bueno_form_group ends -->
                            </div> <!-- col-xs-12 ends -->

                            <div class="col-xs-12 no-padding">
                                <div class="form-group bueno_form_group has-success clearfix">
                                    <div class="col-xs-12 col-md-6 left_btn_sec">
                                        <a href="" class="btn btn-pink icon full_width {{ $item->is_favorite }}" data-id="{{ $item->id }}" data-url="{{ route('users.saved_items.post') }}" data-token="{{ csrf_token() }}">
                                                <span class="add_to_favorite_text">
                                                    @if($item->is_favorite == 'add_to_favourite')
                                                            Save for Later
                                                        @else
                                                            Saved
                                                        @endif
                                                </span>
                                            <i class="ion-android-favorite"></i>
                                        </a>
                                    </div> <!-- left_btn_sec ends -->
                                </div> <!-- bueno_form_group ends -->
                                <div class="col-xs-12 col-md-6 left_btn_sec">
                                    <input type="submit"  data-id="{{ $item->id }}" data-url="{{ route('users.cart.post') }}" data-token="{{ csrf_token() }}" class="btn btn-primary full_width add_deal_to_cart @if($item->itemable->status == 2){{ "disabled" }}@endif" value="Add to Cart">
                                </div> <!-- left_btn_sec ends -->
                            </div> <!-- col-xs-12 ends -->
                        </div> <!-- meal_details ends -->
                    </div> <!-- listing_show ends -->

                </div> <!-- col-xs-12 ends -->
            </div> <!-- row ends -->
        </div> <!-- container ends -->

    </section> <!-- hot_deals_listing_sec ends -->

@if($item->recommended->count())
    <section class="dashed_title_sec">
        <div class="container more">
            <div class="row">
                <div class="main-sec col-xs-12">
                    <h3>You might also like</h3>
                </div> <!-- col-xs-12 ends -->
            </div> <!-- row ends -->
        </div> <!-- container ends -->
    </section> <!-- title_sec ends -->


    <section class="meal_carousel_sec no-paddingtop">
        <div class="container more">
            <div class="row">
                <div class="col-xs-12">
                    <div class="listing_grid">
                        @include('partials/recommended_slider')
                    </div> <!-- listing_grid ends -->

                </div> <!-- col-xs-12 ends -->
            </div> <!-- row ends -->
        </div> <!-- container ends -->
    </section> <!-- title_sec ends -->

    <script>
        fbq('track', 'Lead');
    </script>
@endif
@stop


