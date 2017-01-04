<script type="text/html" id='header-cart'>
    <li class="cart_holder">
        <% if (cart_items.length) { %>
            <a id="scrollToPrevItem" href="javascript:void(0)" class="cart_slider up"><i class="ion-android-arrow-dropup"></i></a>
            <ul class="cart_item_ul header_cart_item_ul">
                <% _.each(cart_items, function(cart_item, key, list){ %>
                    <li class="cart_item_li clearfix">
                        <a href="<%= cart_item.item.item_url %>">
                            <div class="meal_list col-xs-12">
                                <div class="meal_img col-xs-5">
                                    <div class="img-responsive cart_item_image" style="background-image: url(<%= cart_item.item.image_url %>)"></div>
                                </div> <!-- meal_img ends -->
                                <div class="meal_desc col-xs-7">
                                    <form action="<?php echo  route('users.cart.delete') ?>" method="POST">
                                        <?php echo csrf_field() ?>
                                        <input type="hidden" name="_method" value="DELETE"/>
                                        <input type="hidden" name="cart_id" value="<%=  cart_item.id %>"/>
                                        <button class="remove_item remove_from_cart" href=""><i class="ion-ios-close-outline"></i></button>
                                    </form>
                                    <p class="title normal_para"><%= cart_item.item.itemable.name %></p>
                                    <p class="price normal_para">₹ <%= cart_item.item.itemable.discount_price %></p>
                                    <p class="extras normal_para">Qty: <%= cart_item.quantity %></p>
                                </div> <!-- meal_desc ends -->
                            </div> <!-- meal_list ends -->
                        </a>
                    </li> <!-- cart_item_li ends -->
                <% }) %>
            </ul><!-- cart_item_ul ends -->
            <a id="scrollToNextItem" href="javascript:void(0)" class="cart_slider down"><i class="ion-android-arrow-dropdown"></i></a>
            <div class="cart_actions col-xs-12">
                <p class="normal_para text-right total_price"><strong>Total: ₹ <span class="cart_total_amount"></span></strong></p>
                <div class="col-xs-6 no-paddingright paddingleft-sm">
                    <a href="{{ route('users.cart.get') }}" class="btn btn-primary full_width">Checkout</a>
                </div> <!-- col-xs-6 ends -->
            </div> <!-- cart_actions ends -->
        <% } else { %>
            <h4 class="normal_header padding-md text-center"><i class="ion-android-alert paddingright-xs"></i>Your cart is empty </h4>
        <% } %>
    </li> <!-- cart_holder ends -->
    </script>