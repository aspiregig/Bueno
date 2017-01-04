<div class="container">
	<div class="row">
		<div class="col-xs-12">

	  	<div class="catering_menu col-xs-12">

	  		<div class="catering_menu_left col-xs-12 col-sm-6 left-sec">
	  			<h3 class="style_header">Download our <span class="text-yellow">Menu</span></h3>
	  			<p class="normal_para"><em>Global Cuisine / 5 Star Chefs / Completely Customizable</em></p>
	  		</div> <!-- catering_menu_left ends -->

	  		<div class="catering_menu_right col-xs-12 col-sm-6 right-sec">
	  			<div class="col-xs-12 col-xs-offset-0 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-0 no-padding">
		  			<div class="border-block full_width text-left">
		  				<label>Call us on:</label>
		  				<strong>{{ config('bueno.site.catering_phone') }}</strong>
		  			</div> <!-- border-block ends -->
		  			<div class="border-block full_width text-left marginbottom-md">
		  				<label>Email us on:</label>
		  				<strong>{{ config('bueno.site.catering_email') }}</strong>
		  			</div> <!-- border-block ends -->
		  			<a href="{{ route('pages.catering.query.get') }}" class="btn btn-primary-inverse full_width">Submit a Query</a>
		  			<a href="{{ route('pages.catering.download.get') }}" class="btn btn-warning full_width">Download the full Menu</a>
		  		</div> <!-- col-xs-12 ends -->
	  		</div> <!-- catering_menu_right ends -->

	  	</div> <!-- catering_menu ends -->

		</div> <!-- col-xs-12 ends -->
	</div> <!-- row ends -->
</div> <!-- container ends -->