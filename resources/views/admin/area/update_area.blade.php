
@extends('admin.master')

  @section('title')Bueno Kitchen @endsection

  @section('header')

  <!-- stylesheets -->
  @include('admin.partials.css')

  <!-- javascript -->
  @include('admin.partials.js')

    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

  @endsection

  @section('content')

  <div id="content">
  @include('admin.partials.errors')
            <div class="menubar">
                <div class="sidebar-toggler visible-xs">
                    <i class="ion-navicon"></i>
                </div>

                <div class="page-title">
                    Update Area 
                </div>
            </div>

            <div class="content-wrapper">
                @include('admin.partials.flash')
                <form id="new-customer" class="form-horizontal" method="post" role="form">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <input type="hidden" name="id" value="{{$area->id}}"> 
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Area Name</label>
                        <div class="col-sm-10 col-md-8">
                          <input type="text" class="form-control" name="name" value="{{$area->name}}" id="area" required=""/>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Parent City</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="has-feedback">
                                <select name="city_id" class="form-control" id="city" >
                                @foreach($cities as $city)
                                <option value="{{$city->id}}" @if($city->id==$area->city_id) selected="" @endif>{{$city->name}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Pincode</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="text" class="form-control" name="pincode" value = "@if(old('pincode')){{old('pincode')}}@else{{$area->pincode}}@endif"  required=""/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Min Order Amount</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="text" class="form-control" name="min_order_amount" value = "@if(old('min_order_amount')){{old('min_order_amount')}}@else{{$area->min_order_amount}}@endif"  required=""/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Delivery Time (Minutes)</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="text" class="form-control" name="delivery_time" value = "@if(old('delivery_time')){{old('delivery_time')}}@else{{$area->delivery_time}}@endif"  required=""/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Area Latitude</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="text" id="latitude" class="form-control" name="latitude" @if(old('latitude')) value = "{{old('latitude')}}" @else value = "{{$area->latitude}}" @endif/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Area Longitude</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="text" id="longitude" class="form-control" name="longitude" @if(old('longitude')) value = "{{old('longitude')}}" @else value = "{{$area->longitude}}" @endif/>
                        </div>
                    </div>




                    <div id="dc-edit-google-map">
                        <div id="lat-long-selector-map" style="height:200px; border:1px solid #ddd; text-align:center">
                        </div>
                        <p></p>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 control-label">Status</label>
                        <div class="col-sm-10 col-md-8">
                            <div class="has-feedback">
                                <select name="status" class="form-control" id="OrderLocality">
                                    <option value="1" @if($area->status) selected="" @endif>Active</option>
                                    <option value="0"  @if(!$area->status) selected="" @endif>Disabled</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group form-actions">
                        <div class="col-sm-offset-2 col-sm-10">
                            <a href="{{URL::route('admin.areas')}}" class="btn btn-default">Cancel</a>
                            <button class="btn btn-success">Update Area</button>
                        </div>
                    </div>
                </form>
                  
            </div>
        </div>

  @endsection

  @section('script')

      <script src="http://maps.googleapis.com/maps/api/js"></script>
  <script type="text/javascript">
        $(function () {

            // form validation
            $('#new-customer').validate({
                rules: {
                    "customer[first_name]": {
                        required: true
                    },
                    "customer[email]": {
                        required: true,
                        email: true
                    },
                    "customer[address]": {
                        required: true
                    },
                    "customer[notes]": {
                        required: true
                    }
                },
                highlight: function (element) {
                    $(element).closest('.form-group').removeClass('success').addClass('error');
                },
                success: function (element) {
                    element.addClass('valid').closest('.form-group').removeClass('error').addClass('success');
                }
            });

            if($('#dc-edit-google-map').length)
            {
                if($('#latitude').val() && $('#longitude').val()){
                    initializeMap($('#latitude').val(), $('#longitude').val());
                }else{
                    var address = $('#area').text() +',' + $('#city option:selected').text();
                    var geocoder = new google.maps.Geocoder();
                    geocoder.geocode( { 'address': address}, function(results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            $('#latitude').val(results[0].geometry.location.lat());
                            $('#longitude').val(results[0].geometry.location.lng());
                            $('#lat-long-selector-map').parent('div').show();
                            initializeMap(results[0].geometry.location.lat(),results[0].geometry.location.lng());
                        } else {
                            alert("Something got wrong " + status);
                        }
                    });
                }

            }

            function updateMarkerPosition(latLng) {
                $('#latitude').val(latLng.lat());
                $('#longitude').val(latLng.lng());
            }

            function initializeMap(initLat, initLong) {
                var latLng = new google.maps.LatLng(initLat, initLong);
                var map = new google.maps.Map(document.getElementById('lat-long-selector-map'), {
                    zoom: 13,
                    center: latLng,
                    zoomControl: true,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                });
                var marker = new google.maps.Marker({
                    position: latLng,
                    map: map,
                    draggable: true
                });

                // Update current position info.
                updateMarkerPosition(latLng);
                geocodePosition(latLng);


                google.maps.event.addListener(marker, 'drag', function() {
                    updateMarkerPosition(marker.getPosition());
                });

            }

            function geocodePosition(pos) {
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({
                    latLng: pos
                }, function(responses) {
                    if (!responses) {
                        console.log('error');
                    }
                });
            }


        });
    </script>

  @endsection