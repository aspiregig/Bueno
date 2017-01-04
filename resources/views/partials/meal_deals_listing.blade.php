<div class="listing_padding col-xs-12 col-sm-6 col-md-3">
	<div class="listing_item">
		<div class="listing_header">
			<h4 class="title"><a href="{{ $item['item_url'] }}">{{ $item['name'] }}</a></h4>
			<p class="price">&nbsp; @if($item['original_price'] > $item['discount_price'])&#8377; <span class="strike-through">{{ $item['original_price'] }}</span>@endif &nbsp;&#8377; {{ $item['discount_price'] }}/-</p>
		</div> <!-- listing_header ends -->
		<div class="listing_image">
			@if($item['status'] == 2)
                    <div class="side_ribbon"><span>{{ config('bueno.item_status')[2] }}</span></div>
                @endif
                @if($item['stock'] == 0)
                    <div class="side_ribbon"><span>Out of Stock</span></div>
                @endif
			<a href="{{ route('items.xprs-menu.single.get', $item['slug']) }}">
                <span class="item-image" style="background-image:url({{ $item['image_url'] }})"></span>
			</a>
		</div> <!-- listing_image ends -->
		<div class="listing_body">
			<div class="details">
				<p class="desc text-muted">{{ $item['description'] }}</p>
			<p class="extras text-muted">Serves: {{ $item['serves'] }} @if($item['spiceLevel'] )|   Spice: {{ $item['spiceLevelName'] }} @endif </p>
			</div> <!-- details ends -->
		</div> <!-- listing_body ends -->
		<div class="listing_category @if($item['type'] != 1){{"non-veg"}}@else{{"veg"}}@endif"></div> <!-- listing_category ends -->
		<div class="listing_footer">
			<div class="sec_table">
				<div class="sec_table_row">
					<div class="social">
                        <a class="{{ $item['is_favorite'] }}" data-id="{{ $item['id'] }}" data-url="{{ route('users.saved_items.post') }}" data-token="{{ csrf_token() }}" href=""><i class="ion-android-favorite"></i></a>
						<div class="social_dropdown dropdown">
							<a class="share_popup" type="button" id="sharePopup" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
								<!-- <span class="txt">Share</span> -->
								<i class="ion-android-share-alt"></i>
							</a>
							<ul class="dropdown-menu" aria-labelledby="sharePopup">
                                <li class=""><a class="social_facebook share-popover" href="https://www.facebook.com/sharer/sharer.php?u={{ $item['item_url'] }}"><i class="ion-social-facebook"></i></a></li>
                                <li class=""><a class="social_twitter share-popover" href="https://twitter.com/home?status=Checkout !{{ $item['item_url'] }}"><i class="ion-social-twitter"></i></a></li>
                                <li class=""><a class="social_instagram share-popover" href="https://pinterest.com/pin/create/button/?url={{ $item['item_url'] }}&media={{ $item['image_url'] }}&description={{ $item['description'] }}"><i class="ion-social-pinterest"></i></a></li>
                                <li class=""><a class="social_instagram share-popover" href="https://www.linkedin.com/shareArticle?mini=true&url={{ route('items.xprs-menu.single.get', $item['slug'] ) }}={{ $item['name'] }}&summary={{ $item['description'] }}"><i class="ion-social-linkedin"></i></a></li>

                            </ul>
						</div> <!-- social_dropdown ends -->
						<span class="text-muted">Quantity</span>
						<div class="quantity_select sec_table_cell">
							<select id="itemQuantity" class="form-control item_quantity_select">
                                @foreach(range(1, 10) as $value)
                                    <option value="{{ $value }}" @if($value == 1) selected @endif>{{ $value }}</option>
                                @endforeach
							</select>
						</div> <!-- sec_table_cell -->
					</div> <!-- social ends -->
					<a href="" class="btn btn-primary action add_to_cart @if($item['status'] == 2 || $item['stock'] == 0){{ "disabled" }}@endif" data-id="{{ $item['id'] }}" data-token="{{ csrf_token() }}" data-url="{{ route('users.cart.post') }}">Add to Cart</a>
				</div> <!-- sec_table_row ends -->
			</div> <!-- sec_table ends -->
		</div> <!-- listing_footer ends -->
	</div> <!-- listing_item ends -->
</div> <!-- col-xs-12 ends -->