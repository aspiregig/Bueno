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
                        <li><a href="{{ route('pages.about.get') }}" class="btn btn-secondary @if(Route::currentRouteName() == 'pages.about.get'){{"active"}}@endif">About Bueno</a></li>
                        <li><a href="{{ route('pages.press.get') }}" class="btn btn-secondary @if(Route::currentRouteName() == 'pages.press.get'){{"active"}}@endif">In the Press</a></li>
                        <li><a href="{{ route('pages.faq.get') }}" class="btn btn-secondary @if(Route::currentRouteName() == 'pages.faq.get'){{"active"}}@endif">FAQs</a></li>
                        <li><a href="{{ route('pages.privacy_policy.get') }}" class="btn btn-secondary @if(Route::currentRouteName() == 'pages.privacy_policy.get'){{"active"}}@endif">Privacy Policy</a></li>
                        <li><a href="{{ route('pages.terms_conditions.get') }}" class="btn btn-secondary @if(Route::currentRouteName() == 'pages.terms_conditions.get'){{"active"}}@endif">Terms &amp; Conditions</a></li>
                        <li><a href="{{ route('pages.refund_cancellation.get') }}" class="btn btn-secondary @if(Route::currentRouteName() == 'pages.refund_cancellation.get'){{"active"}}@endif">Refunds &amp; Cancellation</a></li>
                    </ul>
                </div> <!-- main-sec ends -->

            </div> <!-- col-xs-12 ends -->
        </div> <!-- row ends -->
    </div> <!-- container ends -->
</section> <!-- title_sec ends -->