
<a href="javascript:void(0)" id="goToTop" class="go_to_top">
    <i class="ion-chevron-up"></i>
</a> <!-- #goToTop ends -->

<!-- ############################## -->
<!-- ########### Header ########### -->
<!-- ############################## -->

<!-- Navigation Bar -->
<nav id="buenoHeader" class="navbar navbar-inverse bueno_header" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ route('pages.index') }}">
                <img class="img-responsive" src="{{ asset('assets/images/bueno_logo_white.png') }}" alt="Bueno Logo">
            </a>
        </div> <!-- navbar-header ends -->
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
            <li><a href="{{ route('items.search.xprs-menu.get') }}">Global Menu</a></li>
                <li><a href="{{ route('pages.offers.get') }}">Offers</a></li>
                <li><a href="{{ route('items.hot_deals.get') }}">Today's Specials</a></li>
                {{-- <li><a href="{{ route('pages.catering.get') }}">Catering</a></li> --}}
                <li class="user_list">
                    @include('partials.header_user_actions')
                    @if(auth()->check())
                        @include('partials.header_user_profile_dropdown')
                    @else
                        @include('partials.header_profile_dropdown')
                    @endif
                </li>
                <li class="mobile_nav_list">
                    @if(auth()->check())
                        @include('partials.header_user_profile_dropdown_mobile')
                    @else
                        @include('partials.header_profile_dropdown')
                    @endif
                </li> <!-- mobile_nav_list ends -->
                <li class="nav-social">
                    <a href="{{ config('bueno.social.facebook') }}" target="_blank"><i class="ion-social-facebook"></i></a>
                    <a href="{{ config('bueno.social.twitter') }}" target="_blank"><i class="ion-social-twitter"></i></a>
                    <a href="{{ config('bueno.social.linkedIn') }}" target="_blank"><i class="ion-social-linkedin"></i></a>
                    <a href="{{ config('bueno.social.instagram') }}" target="_blank"><i class="ion-social-instagram"></i></a>
                </li>
            </ul>
        </div> <!-- navbar-collapse ends -->
    </div><!-- container ends -->
</nav> <!-- navbar ends -->

<div id="pageWrap">