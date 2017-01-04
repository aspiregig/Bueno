<script type="text/html" id="hot-deals-homepage-listing">

    <% _.each(items,function(item,key,list){ %>
        <div class="listing_padding col-xs-12">
            <div class="listing_item">
                <div class="listing_image">
                    <a href="">
                        <img class="img-responsive" src="<%= item.image_url %>" alt="">
                    </a>
                </div> <!-- listing_image ends -->
                <div class="listing_body">
                    <div class="details">
                        <h4 class="title"><%= item.name %></h4>
                        <p class="desc text-muted"><%= item.description %></p>
                        <p class="extras text-muted">Serves: <%= item.serves %> | Spice: Mild</p>
                    </div> <!-- details ends -->
                    <div class="price">
                        <p>&#8377; <%= item.discount_price %>/-</p>
                    </div> <!-- price ends -->
                </div> <!-- listing_body ends -->
                <div class="listing_category non-veg"></div> <!-- listing_category ends -->
                <div class="listing_footer">
                    <div class="sec_table">
                        <div class="sec_table_row">
                            <div class="social">
                                <a class="favourite add_to_favorite" data-id="<%= item.id %>" data-url="{{ route('users.saved_items.post') }}" data-token="{{ csrf_token() }}" href=""><i class="ion-android-favorite"></i></a>
                                <div class="social_dropdown dropdown">
                                    <a class="share_popup" type="button" id="sharePopup" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        <i class="ion-android-share-alt"></i>
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="sharePopup">
                                        <li><a class="social_facebook" href=""><i class="ion-social-facebook"></i></a></li>
                                        <li><a class="social_twitter" href=""><i class="ion-social-twitter"></i></a></li>
                                        <li><a class="social_linkedin" href=""><i class="ion-social-linkedin"></i></a></li>
                                        <li><a class="social_instagram" href=""><i class="ion-social-instagram"></i></a></li>
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
                            <a href="" class="btn btn-primary action add_to_cart" data-token="{{ csrf_token() }}" data-id="<%= item.id %>" data-url="{{ route('users.cart.post') }}">Add to Cart</a>
                        </div> <!-- sec_table_row ends -->
                    </div> <!-- sec_table ends -->
                </div> <!-- listing_footer ends -->
            </div> <!-- listing_item ends -->
        </div> <!-- col-xs-12 ends -->
    <% }) %>
</script>