<div class="meal_carousel carousel_global" id="homepage_hot_deals_container">

	@foreach($hot_deals as $item)

        <div class="listing_padding col-xs-12">
            <div class="listing_item">
                <div class="listing_image">
                    <a href="{{ $item->item_url }}">
                        <span class="item-image" style="background-image:url({{ $item->image_url }})"></span>
                    </a>
                </div> <!-- listing_image ends -->
                <div class="listing_body">
                    <div class="details">
                        <h4 class="title">{{ $item->itemable->name }}</h4>
                        <p class="desc text-muted">{{ $item->itemable->description }}</p>
                        <p class="extras text-muted">Serves:{{ $item->itemable->serves }} | Spice: Mild</p>
                    </div> <!-- details ends -->
                    <div class="price">
                        <p>&#8377; {{ $item->itemable->discount_price }}/-</p>
                    </div> <!-- price ends -->
                </div> <!-- listing_body ends -->
                <div class="listing_category non-veg"></div> <!-- listing_category ends -->
                <div class="listing_footer">
                    <div class="sec_table">
                        <div class="sec_table_row">
                            <div class="social">
                                <a class="{{ $item->is_favorite }}" data-id="{{ $item->id }}" data-url="{{ route('users.saved_items.post') }}" data-token="{{ csrf_token() }}" href=""><i class="ion-android-favorite"></i></a>
                                <div class="social_dropdown dropdown">
                                    <a class="share_popup" type="button" id="sharePopup" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        <i class="ion-android-share-alt"></i>
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="sharePopup">
                                        <li class=""><a class="social_facebook share-popover" href="https://www.facebook.com/sharer/sharer.php?u={{ $item->item_url }}"><i class="ion-social-facebook"></i></a></li>
                                        <li class=""><a class="social_twitter share-popover" href="https://twitter.com/home?status=Checkout !{{ $item->item_url }}"><i class="ion-social-twitter"></i></a></li>
                                        <li class=""><a class="social_pinterest share-popover" href="https://pinterest.com/pin/create/button/?url={{ $item->item_url }}&media={{ $item->image_url }}&description={{ $item->itemable->description }}"><i class="ion-social-pinterest"></i></a></li>
                                        <li class=""><a class="social_instagram share-popover" href="https://www.linkedin.com/shareArticle?mini=true&url={{ route('items.xprs-menu.single.get', $item->itemable->slug ) }}={{ $item->itemable->name }}&summary={{ $item->itemable->description }}"><i class="ion-social-linkedin"></i></a></li>

                                    </ul>
                                </div> <!-- social_dropdown ends -->
                                <span class="text-muted">Quantity</span>
                                <div class="quantity_select sec_table_cell">
                                    <select id="itemQuantity" class="form-control item_quantity_select">
                                        <option value="1" selected>1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div> <!-- sec_table_cell -->
                            </div> <!-- social ends -->
                            <a href="" class="btn btn-primary action add_to_cart @if($item->itemable->status == 2){{ "disabled" }}@endif" data-token="{{ csrf_token() }}" data-id="{{ $item->id }}" data-url="{{ route('users.cart.post') }}">Add to Cart</a>
                        </div> <!-- sec_table_row ends -->
                    </div> <!-- sec_table ends -->
                </div> <!-- listing_footer ends -->
            </div> <!-- listing_item ends -->
        </div> <!-- col-xs-12 ends -->
	
	@endforeach

</div> <!-- meal_carousel ends -->

@if(count($hot_deals) == 0)
    <div class="col-xs-12 placeholder_message text-center">
        <h4 class="normal_header"><i class="ion-android-alert paddingright-xs"></i>Sorry, there are no results to show.</h4>
    </div> <!-- placeholder_message ends -->
@endif