<div class="testimonial_carousel carousel_global">

	@foreach($testimonials as $testimonial)

	<div class="testimonial_holder col-xs-12">
		<div class="user col-xs-12 col-sm-4 col-md-3 left-sec">
			<h3 class="name">{{ $testimonial->full_name }}</h3>
			<div class="info">
				<span class="review">{{$testimonial->special_text}}</span>
			</div> <!-- info ends -->
		</div> <!-- user ends -->
		<div class="testimonial col-xs-12 col-sm-8 col-md-9 right-sec">
      <p>{{ $testimonial->content }}</p>
		</div> <!-- testimonial ends -->
	</div> <!-- testimonial_holder ends -->

	@endforeach

</div> <!-- testimonial_carousel -->