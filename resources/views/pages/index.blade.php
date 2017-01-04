    @extends('layouts.master')

    @section('content')

        <!-- ############################## -->
        <!-- ############ BODY ############ -->
        <!-- ############################## -->

        @include('partials.header_flash')
        @if($sliders->count())
        <section id="mainCarouselSection">
            <div class="header_carousel">


                @foreach($sliders as $slider)

                <a href="{{ $slider->link_url }}" class="carousel_bg_image" style="background-image: url({{ route('photo.home_slider', $slider->image_url) }})">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="carousel_caption">
                                    <h2 class="carousel_title">{{ $slider->name }}</h2>
                                    <p class="carousel_desc">{!! $slider->description !!}</p>
                                </div> <!-- carousel_content ends -->
                            </div> <!-- col-xs-12 ends -->
                        </div> <!-- row ends -->
                    </div> <!-- container ends -->
                </a> <!-- carousel_bg_image ends -->

                @endforeach

            </div> <!-- header_carousel ends -->
        </section> <!-- #carouselSection ends -->
        @endif
        @if($ad_text)
        <section class="highlight_sec">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="sec_table col-xs-12">
                            <div class="sec_table_row">
                                    {!! $ad_text->html_content !!}
                            </div> <!-- sec_table_row ends -->
                        </div> <!-- sec_table ends -->
                    </div> <!-- col-xs-12 ends -->
                </div> <!-- row ends -->
            </div> <!-- container ends -->
        </section> <!-- inroductry_sec ends -->
        @endif

        @include('partials.locality_select')

        @if($banners->count())
        <section class="deals_sec">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">

                        @foreach($banners as $banner)
                        <a href="{{ $banner->link_url }}">
                          <div class="special_banner col-xs-12">
                            <div class="special_image" style="background: linear-gradient(90deg, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.1)), url('{{ route('photo.banner', $banner->display_image_url) }}');">
                                <h3 class="banner_title">{{ $banner->content }}</h3> <!-- banner_title ends -->
                                <span class="btn banner_cta">Order Now</span> <!-- banner_cta ends -->
                            </div> <!-- special_image ends -->
                        </div> <!-- special_banner ends -->
                        </a>
                         @endforeach

                    </div> <!-- col-xs-12 ends -->
                </div> <!-- row ends -->
            </div> <!-- container ends -->
        </section> <!-- main_sec ends -->
        @endif

        <section class="title_sec">
            <div class="container more">
                <div class="row">
                    <div class="main-sec col-xs-12">
                        <div class="col-sm-12 col-md-6 left-sec">
                            <h2 class="style_header">Our Global Menu</h2>
                        </div> <!-- left-sec ends -->
                        <div class="col-sm-12 col-md-6 right-sec">
                            {{--<p class="normal_para"></p>--}}
                        </div> <!-- right-sec ends -->
                    </div> <!-- col-xs-12 ends -->
                </div> <!-- row ends -->
            </div> <!-- container ends -->
        </section> <!-- title_sec ends -->

        <section class="xprs_menu_listing_sec homepage_items">

            @include('partials.xprs_menu')

        </section> <!-- hot_deals_listing_sec ends -->



        @include('templates/xprs_menu_listing')

    @endsection