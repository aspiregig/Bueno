<div class="container more">
    <div class="row">
        <div class="col-xs-12">

            <!-- Listing Show -->
            <div class="filters col-xs-12 col-md-3">
                <div class="sidebar-filters">
                    <div class="well no-marginbottom">
                        <h4>Filter your search</h4>
                    </div> <!-- well ends -->
                    <form action="{{ route('items.search.xprs-menu.get') }}" id="form-left">
                        <div class="filter_menu">

                            <ul class="filter_ul filter_option clear_filter_option @if(request()->has('cuisine') || request()->has('availability') || request()->has('category')){{ "show" }}@endif">
                                <a class="holder clear_filters" href="">
                                    <li>
                                        <label>
                                            <span class="txt">Clear All Filters</span>
                                        </label>
                                        <i class="ion-close-circled"></i>
                                    </li> <!-- <li> ends -->
                                </a> <!-- holder ends -->
                            </ul><!-- filter_ul ends -->

                            <ul class="filter_ul">
                                <li class="selected_filters">
                                    <ul>
                                        @foreach($selected_filters['availabilities'] as $availability)
                                            <li class="checkbox_holder">
                                                <label>
                                                    <span class="txt">{{ $availability->name }}</span>
                                                    <input type="checkbox" name="availability[]" class="inputcheckbox" checked value="{{ $availability->id }}" />
                                                    <span class="check_style"></span>
                                                </label>
                                            </li>
                                        @endforeach
                                        @foreach($selected_filters['categories'] as $category)
                                            <li class="checkbox_holder">
                                                <label>
                                                    <span class="txt">{{ $category->name }}</span>
                                                    <input type="checkbox" name="category[]" class="inputcheckbox" checked value="{{ $category->id }}" />
                                                    <span class="check_style"></span>
                                                </label>
                                            </li>
                                        @endforeach

                                    </ul>
                                </li>


                                <li class="filter_li">
                                    <label class="bueno_select no_caret">
                                        <select  class="full_width no-marginbottom bueno_select2 xprs_category_select2 bueno_select2_xprs_menu">
                                            <option value="">Choose by Category</option>
                                            @foreach($filters['categories'] as $category)
                                                <option value="{{ $category->id }}">{{ $category->name  }}</option>
                                            @endforeach
                                        </select>
                                    </label> <!-- bueno_select ends -->
                                </li> <!-- filter_li ends -->
                               <!--  <li class="filter_li">
                                    <label class="bueno_select no_caret">
                                        <select  id="locality" class="full_width no-marginbottom bueno_select2 xprs_course_select2 bueno_select2_xprs_menu">
                                            <option value="">Choose by Course</option>
                                            @foreach($filters['availabilities'] as $key => $availability)
                                                <option value="{{ $availability->id }}">{{ $availability->name  }}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                </li>  --><!-- filter_li ends -->
                                {{--<li class="filter_li">--}}
                                {{--<div class="custom_slider">--}}
                                {{--<label class="marginbottom-lg" for="priceRange">Price Range</label>--}}
                                {{--<input id="priceRange" type="text"  class="xprs_price_range" name="price" class="span2" value="" data-slider-min="1" data-slider-max="1000" data-slider-step="5" data-slider-value="[1, 1000]" data-slider-handle="bar"/>--}}
                                {{--<b class="range_min">₹ 1</b>--}}
                                {{--<b class="range_max">₹ 1000</b>--}}
                                {{--</div> <!-- custom_slider ends -->--}}
                                {{--</li>--}}
                            </ul> <!-- filter_ul ends -->
                        </div> <!-- filter_menu ends -->
                    </form>
                </div>
            </div> <!-- filters ends -->


            <!-- Listing Items -->
            <div class="col-xs-12 col-md-9 listing_grid marginbottom-md no-padding">

                <div class="extra_filters col-xs-12 marginbottom-md">
                    <form action="{{ route('items.search.xprs-menu.get') }}" id="form-right" class="xprs_menu_form">
                        <div class="col-xs-6 col-sm-6 col-md-3 filter_sort">
                            <label class="bueno_select no_caret">
                                <select name="sort" id="locality" class="full_width no-marginbottom xprs_sort_select2 bueno_select2">
                                    <option value="">Sort By</option>
                                    <option value="popular">Popular</option>
                                    <option value="recommended">Recommended</option>
                                    <option value="price-high-low">Price High to Low</option>
                                    <option value="price-low-high">Price Low to High</option>
                                </select>
                            </label> <!-- bueno_select ends -->
                        </div> <!-- col-xs-12 ends -->
                        <div class="col-xs-6 col-sm-6 col-md-3 filter_category">
                            <label class="bueno_select no_caret">
                                <select name="type" id="locality" class="full_width no-marginbottom bueno_select2 xprs_type_select2 bueno_select2_xprs_menu">
                                    <option value="">Choose by Type</option>
                                    @foreach($filters['types'] as $type)
                                        <option value="{{ $type->id }}">{{ $type->name  }}</option>
                                    @endforeach
                                </select>
                            </label> <!-- bueno_select ends -->
                        </div> <!-- col-xs-12 ends -->
                        <div class="col-xs-12 col-md-4 filter_search">
                            <div class="input-group bueno_inputgroup full_width icon_right">
                                <input type="text" class="bueno_inputtext black xprs_search_by_keyword full_width" name="keyword" placeholder="Search for an item">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button"><i class="ion-ios-search-strong"></i></button>
                                        </span>
                            </div><!-- /input-group -->
                        </div> <!-- col-xs-12 ends -->
                        <div class="col-xs-12 col-md-2 loading-items">
                            <div class="">
                                <i class="ion-load-c"></i>
                            </div>
                        </div>
                    </form>
                </div> <!-- extra_filters ends -->

                <div class="col-xs-12 listing_xprs_menu" id="xprs_menu_container">
                    @foreach($items['items'] as $item)
                        <div class="listing_padding col-xs-12 col-sm-6 col-md-4">
                            @include('partials/meal_listing')
                        </div> <!-- listing_padding ends -->
                    @endforeach
                    @if(count($items['items']) == 0)
                        <div class="col-xs-12 placeholder_message">
                            <h4 class="normal_header"><i class="ion-android-alert paddingright-xs"></i>Sorry, there are no results to show.</h4>
                        </div> <!-- placeholder_message ends -->
                    @endif

                </div> <!-- col-xs-12 ends -->
            </div> <!-- listing_deals ends -->

        </div> <!-- col-xs-12 ends -->
    </div> <!-- row ends -->
</div> <!-- container ends -->