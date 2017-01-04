<div class="container more">
	<div class="row">
		<div class="col-xs-12">

			<div class="col-xs-12 locality home_deal_area">
				<form action="{{ route('items.search.hot_deals.area.post') }}" class="form-horizontal locality_form">
					{{ csrf_field() }}
                    <div class="form-group bueno_form_group no-marginbottom">
                        @if(session()->has('area_id') && in_array(session('area_id'), $areas->pluck('id')->toArray()))
                            <label for="locality" class="col-xs-12 col-sm-4 col-md-5 control-label">Locality for delivery: <span class="locality_area_name">{{ $area->name }}</span> | <span>Minimum Order &#8377; <span class="locality_minimum_price">{{ $area->min_order_amount }}</span>/-</span></label>
                        @else
                            <label for="locality" class="col-xs-12 col-sm-4 col-md-3 control-label">Please select your locality for delivery</label>
                        @endif
				    <div class="col-xs-12 col-sm-3">
				    	<label class="bueno_select no_caret">
					    	<select name="area_id" class="full_width no-marginbottom locality_select2">
                                @foreach($areas as $area)
                                    <option value="{{ $area->id }}" @if(session()->has('area_id') && session('area_id') == $area->id){{ "selected"}}@endif>{{ $area->name }}</option>
                                @endforeach
					    	</select>
				    	</label> <!-- bueno_select ends -->
				    </div>
				    <div class="col-xs-12 col-sm-3">
				    	<a href="{{ route('items.hot_deals.get') }}" class="btn btn-primary-outline">View All Deals</a>
				    </div>
				  </div> <!-- bueno_form_group ends -->
				</form> <!-- locality_form ends -->
			</div> <!-- locality ends -->

			<div class="col-xs-12 listing_grid listing_deals margintop-lg no-padding">
                @include('partials/meal_slider')
	  	    </div> <!-- listing_deals ends -->

		</div> <!-- col-xs-12 ends -->
	</div> <!-- row ends -->
</div> <!-- container ends -->