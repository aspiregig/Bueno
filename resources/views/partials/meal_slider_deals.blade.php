<div class="meal_carousel carousel_global">

  @foreach($hot_deals as $item)

	<div class="listing_padding col-xs-12">
		<div class="listing_item">
			<div class="listing_header">
				<h4 class="title">Paneer Tawa Masala with Roomali Roti</h4>
				<p class="price">&#8377; 259/-</p>
			</div> <!-- listing_header ends -->
			<div class="listing_image">
				<a href="">
					<img class="img-responsive" src="assets/images/items/paneer-tawa-masala.jpg" alt="">
				</a>
			</div> <!-- listing_image ends -->
			<div class="listing_body">
				<div class="details">
					<p class="desc text-muted">Paneer Cubes cooked in Tomato &amp; Onion gravy flavoured with Whole India Spices. Served with 4 Roomali Rotis... </p>
					<p class="extras text-muted">Serves: 1 | Spice: Mild</p>
					<p class="extras text-muted"><em>Offer valid till 20th January 2015</em></p>
				</div> <!-- details ends -->
			</div> <!-- listing_body ends -->
			<div class="listing_category veg"></div> <!-- listing_category ends -->
			<div class="listing_footer">
				<div class="sec_table">
					<div class="sec_table_row">
						<div class="social">
							<a href=""><i class="ion-android-favorite"></i></a>
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
								<select id="itemQuantity" class="form-control">
									<option value="1" selected>1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
								</select>
							</div> <!-- sec_table_cell -->
						</div> <!-- social ends -->
						<a href="" class="btn btn-primary action">Add to Cart</a>
					</div> <!-- sec_table_row ends -->
				</div> <!-- sec_table ends -->
			</div> <!-- listing_footer ends -->
		</div> <!-- listing_item ends -->
	</div> <!-- col-xs-12 ends -->
	
	@endforeach

</div> <!-- meal_carousel ends -->