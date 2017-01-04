<section class="title_sec gray-dim-bg">
    <div class="container more">
        <div class="row">
            <div class="col-xs-12">
                <div class="col-xs-12 locality locality_xprs_menu">
                    <form action="{{ route('users.area.post') }}" method="POST" class="form-horizontal locality_form">
                        <div class="form-group bueno_form_group no-marginbottom">
                            @if(session()->has('area_id'))
                                <label for="locality" class="col-xs-12 col-sm-6 col-md-5 control-label">Locality for delivery: <span class="locality_area_name">{{ $area->name }}</span> | <span>Minimum Order &#8377; <span class="locality_minimum_price">{{ $area->min_order_amount }}</span>/-</span></label>
                            @else
                                <label for="locality" class="col-xs-12 col-sm-6 col-md-3 control-label">Please select your locality for delivery in Gurgaon</label>
                            @endif
                            <div class="col-xs-12 col-xs-offset-0 col-sm-6 col-sm-offset-0 col-md-4 col-md-offset-2">
                                <label class="bueno_select no_caret">
                                    {{ csrf_field() }}
                                    <select name="area_id" id="locality" class="full_width no-marginbottom locality_select2">
                                        @foreach($areas as $area)
                                            @if(session()->has('area_id') && session('area_id') == $area->id)<option value="{{ $area->id }}" selected>{{ $area->name }}</option>@endif
                                        @endforeach
                                    </select>
                                </label> <!-- bueno_select ends -->
                            </div> <!-- col-xs-12 ends -->
                        </div> <!-- bueno_form_group ends -->
                    </form> <!-- locality_form ends -->
                </div> <!-- locality ends -->

            </div> <!-- col-xs-12 ends -->
        </div> <!-- row ends -->
    </div> <!-- container ends -->
</section> <!-- title_sec ends -->
