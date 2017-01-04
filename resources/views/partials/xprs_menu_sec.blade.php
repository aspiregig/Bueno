<div class="container">
	<div class="row">
		<div class="col-xs-12">

			<div class="col-xs-12 locality">
				<form  action="{{ route('users.area.post') }}" class="form-horizontal locality_form" method="POST">
					<div class="form-group bueno_form_group no-marginbottom">
				    <label for="locality" class="col-xs-12 col-sm-4 control-label">Please select your locality for delivery</label>
				    <div class="col-xs-12 col-sm-4">
				    	<label class="bueno_select no_caret">
                {{ csrf_field() }}
                <select name="area_id" id="locality" class="full_width no-marginbottom locality_select2">
                  <option value="default">Select locality</option>
                  @foreach($areas as $area)
                  <option value="{{ $area->id }}" @if(session()->has('area_id') && session('area_id') == $area->id){{ "selected"}}@endif>{{ $area->name }}</option>
                  @endforeach
                </select>
				    	</label> <!-- bueno_select ends -->
				    </div>
				    <div class="col-xs-12 col-sm-4">
				    	<a href="{{ route('items.search.xprs-menu.get') }}" class="btn btn-primary-outline">View All deals</a>
				    </div>
				  </div> <!-- bueno_form_group ends -->
				</form> <!-- locality_form ends -->
			</div> <!-- locality ends -->

	  	<div class="category_chooser col-xs-12 no-padding">

	  		<div class="chooser col-xs-12 col-sm-4">
	  			<h3>Choose by <span class="text-yellow">Cuisine</span></h3>
	  			<label class="bueno_select no_caret">
                    <form action="{{ route('items.search.xprs-menu.get') }}">
                        <select name="cuisine[]" id="locality" class="full_width no-marginbottom bueno_select2 homepage_choose_select" >
                            <option value="">Choose by Cuisine</option>
                            @foreach($filters['cuisines'] as $cuisine)
                                <option value="{{ $cuisine->id }}">{{ $cuisine->name  }}</option>
                            @endforeach
                        </select>
                    </form>
		    	</label> <!-- bueno_select ends -->
	  		</div> <!-- chooser ends -->

	  		<div class="chooser col-xs-12 col-sm-4">
	  			<h3>Choose by <span class="text-yellow">Category</span></h3>
	  			<label class="bueno_select no_caret">
                    <form action="{{ route('items.search.xprs-menu.get') }}">
                        <select name="category[]" id="locality" class="full_width no-marginbottom bueno_select2 homepage_choose_select">
                            <option value="">Choose by Category</option>
                            @foreach($filters['categories'] as $category)
                                <option value="{{ $category->id }}">{{ $category->name  }}</option>
                            @endforeach
                        </select>
                    </form>
		    	</label> <!-- bueno_select ends -->
	  		</div> <!-- chooser ends -->

	  		<div class="chooser col-xs-12 col-sm-4">
	  			<h3>Choose by <span class="text-yellow">Course</span></h3>
	  			<label class="bueno_select no_caret">
                    <form action="{{ route('items.search.xprs-menu.get') }}">
                        <select name="availability[]" id="locality" class="full_width no-marginbottom bueno_select2 homepage_choose_select">
                            <option value="">Choose by Course</option>
                            @foreach($filters['availabilities'] as $key => $availability)
                                <option value="{{ $availability->id }}">{{ $availability->name  }}</option>
                            @endforeach
                        </select>
                    </form>
		    	</label> <!-- bueno_select ends -->
	  		</div> <!-- chooser ends -->

	  	</div> <!-- category_chooser ends -->

		</div> <!-- col-xs-12 ends -->
	</div> <!-- row ends -->
</div> <!-- container ends -->