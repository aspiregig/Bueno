<div class="meal_carousel carousel_global">

    @foreach($offers as $coupon)

    <div class="listing_padding col-xs-12">
            <div class="listing_item">
                <div class="coupon_header">
                    <h4 class="title">{{ $coupon->code }}</h4>
                </div> <!-- listing_header ends -->
                <div class="listing_body">
                    <div class="details coupon-short-text">
                        <p class="desc text-muted">{{ $coupon->description  }}</p>
                    </div> <!-- details ends -->
                </div> <!-- listing_body ends -->
                <div class="coyp-coupon">
                    <button class="clipboard" data-clipboard-text="{{ $coupon->code }}">Copy Code</button>
                </div>
            </div> <!-- listing_item ends -->
    </div> <!-- col-xs-12 ends -->

    @endforeach

</div> <!-- meal_carousel ends -->