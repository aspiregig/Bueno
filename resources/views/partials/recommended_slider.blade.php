<div class="meal_carousel carousel_global">

    @foreach($item->recommended->filter(function($item){ return $item->itemable->is_sellable == 1 && $item->itemable->status > 0; })as $item)

        <div class="listing_padding col-xs-12">
            <div class="listing_item">
                <div class="listing_image">
                    @if($item->itemable->status == 2)
                        <div class="side_ribbon"><span>{{ config('bueno.item_status')[2] }}</span></div>
                    @endif
                    <a href="{{ route('items.xprs-menu.single.get', $item->itemable->slug) }}">
                        <span class="item-image" style="background-image:url({{ $item->image_url }})"></span>
                    </a>
                </div> <!-- listing_image ends -->
                <div class="listing_body">
                    <div class="details">
                        <h4 class="title">{{ $item->itemable->name }}</h4>
                        <p class="desc text-muted">{{ $item->itemable->description }}</p>
                        <p class="extras text-muted">Serves: {{ $item->itemable->serves }} @if($item->itemable->spiceLevel)|   Spice: {{ $item->itemable->spiceLevel->name }} @endif</p>
                    </div> <!-- details ends -->
                    <div class="price">
                        <p>&#8377; {{ $item->itemable->discount_price }}/-</p>
                    </div> <!-- price ends -->
                </div> <!-- listing_body ends -->
                <div class="listing_category @if($item->itemable->type != 1){{"non-veg"}}@else{{"veg"}}@endif"></div> <!-- listing_category ends -->
                <div class="listing_footer">
                    <div class="sec_table">
                        <div class="sec_table_row">
                            <div class="social">
                                <a class="@if(auth()->check() && in_array($item->id, auth()->user()->saved_items->pluck('item_id')->toArray())){{ "favourite" }}@endif add_to_favorite" data-id="{{ $item->id }}" data-url="{{ route('users.saved_items.post') }}" data-token="{{ csrf_token() }}" href=""><i class="ion-android-favorite"></i></a>
                                <div class="social_dropdown dropdown">
                                    <a class="share_popup" type="button" id="sharePopup" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        <i class="ion-android-share-alt"></i>
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="sharePopup">
                                        <li class=""><a class="social_facebook" href="https://www.facebook.com/sharer/sharer.php?u={{ route('items.xprs-menu.single.get', $item->itemable->slug ) }}"><i class="ion-social-facebook"></i></a></li>
                                        <li class=""><a class="social_twitter" href="https://twitter.com/home?status=Checkout ! {{ route('items.xprs-menu.single.get', $item->itemable->slug ) }}"><i class="ion-social-twitter"></i></a></li>
                                        <li class=""><a class="social_pinterest" href="https://pinterest.com/pin/create/button/?url={{ route('items.xprs-menu.single.get', $item->itemable->slug ) }}&media=@if($item->itemable_type == 'App\Models\Meal'){{ route('photo.meals',$item->itemable->display_pic_url) }}@else{{ route('photo.combos',$item->itemable->display_pic_url) }}@endif&description={{ $item->itemable->description }}"><i class="ion-social-pinterest"></i></a></li>
                                        <li><a class="social_instagram" href="https://www.linkedin.com/shareArticle?mini=true&url={{ route('items.xprs-menu.single.get', $item->itemable->slug ) }}={{ $item->itemable->name }}&summary={{ $item->itemable->description }}="><i class="ion-social-linkedin"></i></a></li>
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
                            <a href="" class="btn btn-primary action add_to_cart @if($item->itemable->status == 2){{ "disabled" }}@endif"" data-token="{{ csrf_token() }}" data-id="{{ $item->id }}" data-url="{{ route('users.cart.post') }}">Add to Cart</a>
                        </div> <!-- sec_table_row ends -->
                    </div> <!-- sec_table ends -->
                </div> <!-- listing_footer ends -->
            </div> <!-- listing_item ends -->
        </div> <!-- col-xs-12 ends -->

    @endforeach

</div> <!-- meal_carousel ends -->