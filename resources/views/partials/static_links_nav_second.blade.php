<section class="hero_section">
    <div class="container more">
        <div class="row">
            <div class="col-xs-12">
                <div class="main-sec text-center">
                    <h3 class="style_header text-white">Eat something new everyday</h3>
                </div> <!-- main-sec ends -->
            </div> <!-- col-xs-12 ends -->
        </div> <!-- row ends -->
    </div> <!-- container ends -->
</section> <!-- title_sec ends -->

<section class="title_sec static_links_nav gray-dim-bg">
    <div class="container more">
        <div class="row">
            <div class="col-xs-12">

                <div class="col-xs-12">
                    <ul class="static_page_links">
                        <li><a href="{{ route('pages.contact.get') }}" class="btn btn-secondary @if(Route::currentRouteName() == 'pages.contact.get'){{"active"}}@endif">Contact Us</a></li>
                        {{--<li><a href="{{ route('pages.catering.enquiry.get') }}" class="btn btn-secondary @if(Route::currentRouteName() == 'pages.catering.enquiry.get'){{"active"}}@endif">Enquiries</a></li>--}}
                        <li><a href="{{ route('pages.catering.get') }}" class="btn btn-secondary @if(Route::currentRouteName() == 'pages.catering.get'){{"active"}}@endif">Catering</a></li>
                        <li><a href="{{ route('pages.corporate_orders.get') }}" class="btn btn-secondary @if(Route::currentRouteName() == 'pages.corporate_orders.get'){{"active"}}@endif">Corporate Orders</a></li>
                        <li><a href="{{ route('pages.careers.get') }}" class="btn btn-secondary @if(Route::currentRouteName() == 'pages.careers.get'){{"active"}}@endif">Careers</a></li>
                    </ul>
                </div> <!-- main-sec ends -->

            </div> <!-- col-xs-12 ends -->
        </div> <!-- row ends -->
    </div> <!-- container ends -->
</section> <!-- title_sec ends -->