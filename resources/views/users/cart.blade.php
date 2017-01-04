@extends('layouts.master')

@section('content')

@include('partials.locality_select')

    <section class="cart_sec">

    <section class="title_sec white-bg">
        <div class="container more">
            <div class="row">

                @include('partials.errors')
                @include('partials.flash')

                <div class="main-sec stick_lines col-xs-12">
                    <div class="col-sm-12 col-md-9 left-sec">
                        <h2 class="style_header_loud">Cart</h2>
                    </div> <!-- left-sec ends -->
                    <div class="col-sm-12 col-md-3 right-sec">
                    </div> <!-- right-sec ends -->
                </div> <!-- col-xs-12 ends -->
            </div> <!-- row ends -->
        </div> <!-- container ends -->
    </section> <!-- title_sec ends -->

    <div class="container more">
        <div class="row">
            <form action="{{ route('checkout.post') }}" method="POST" class="cart_page_form">
                <input type="hidden" name="phone" value="{{ auth()->user()->phone }}"/>
                <input type="hidden" class="form-control" name="source_id" required="" value="{{config('bueno.source.web')}}"/>
                <input type="hidden" name="session_area_id" value="{{ $session_area->id }}"/>
                <input type="hidden" name="min_order_amount"  class="min_order_amount" value="{{ $session_area->min_order_amount }}"/>
                @if(auth()->user()->cartItems->count())
                    {{ csrf_field() }}
                    <div class="col-xs-12">

                        <div class="table-responsive cart_table_responsive">
                            <table class="table table-bordered cart_table no-marginbottom">
                                <thead>
                                <tr>
                                    <th>Item</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-center">Rate</th>
                                    <th class="text-center">Price</th>
                                </tr>
                                </thead>
                                <tbody class="cart_item_container">
                                @foreach($cart_items as $cart_item)
                                    <tr class="cart_item_row @if($cart_item->item->itemable->status == 0 || $cart_item->item->itemable->status == 2 ){{ "disabled_item" }}@endif">
                                        <td class="product_detail">
                                            <input type="hidden" name="items[{{ $cart_item->id }}][id]" value="{{ $cart_item->item->id }}"/>
                                            <input type="hidden" name="cart_id" value="{{ $cart_item->id }}"/>
                                            <input type="hidden" name="cart_item_stock" class="cart_item_stock" value="{{ $cart_item->item->stock() }}"/>
                                            <input type="hidden" class="cart_item_rate" value="{{ $cart_item->item->itemable->discount_price }}"/>
                                            <div class="sec_table">
                                                <div class="image sec_table_cell">
                                                    <a href="{{ $cart_item->item->item_url }}">
                                                        <div class="cart_page_item_image" style="background-image: url({{ $cart_item->item->image_url }})"></div>
                                                    </a>
                                                </div> <!-- image ends -->
                                                <div class="details sec_table_cell">
                                                    <div class="details_holder">
                                                        <h4 class="title"> <a href="{{ $cart_item->item->item_url }}">{{ $cart_item->item->itemable->name }}</a></h4>
                                                        <div class="action_container col-xs-12">
                                                            <div class="btn_sec col-xs-6 cart_item_remove">
                                                                <a href="" class="btn btn-pink icon marginbottom-sm {{ $cart_item->item->is_favorite }}" data-id="{{ $cart_item->item->id }}" data-url="{{ route('users.saved_items.post') }}" data-token="{{ csrf_token() }}">Save for Later<i class="ion-android-favorite"></i></a><br />
                                                                <a href="" class="btn btn-red icon remove_from_cart_page" data-id="{{ $cart_item->id }}" data-url="{{ route('users.cart.delete') }}" data-token="{{ csrf_token() }}">Remove<i class="ion-close"></i></a>
                                                            </div> <!-- btn_sec ends -->
                                                            <div class="alert_sec col-xs-6 cart_item_error">
                                                                </div>
                                                        </div> <!-- action_container ends -->
                                                    </div> <!-- details_holder ends -->
                                                </div> <!-- details ends -->
                                            </div> <!-- sec_table ends -->
                                        </td> <!-- product_detail ends -->
                                        <td class="product_quantity">
                                            <input type="number" placeholder="Qty." class="bueno_inputtext gray cart_item_quantity"  name="items[{{ $cart_item->id }}][quantity]" value="{{ $cart_item->quantity }}" data-cart="{{ $cart_item->id }}" data-token="{{ csrf_token() }}" data-url="{{ route('users.cart.quantity.post') }}">
                                            <br />
                                        </td> <!-- product_quantity ends -->
                                        <td class="product_rate">
                                            <input type="hidden" name="items[{{ $cart_item->id  }}][price]" value="{{ $cart_item->item->itemable->discount_price }}"/>
                                            <span class="price">₹ {{ $cart_item->item->itemable->discount_price }}</span>
                                        </td> <!-- product_rate ends -->
                                        <td class="product_price">
                                            <span class="price">₹ <span class="cart_item_price">{{ round($cart_item->item->itemable->discount_price * $cart_item->quantity, 2) }}</span></span>
                                        </td> <!-- product_price ends -->
                                    </tr>
                                @endforeach
                                </tbody>
                            </table> <!-- cart_table ends -->
                        </div> <!-- table-responsive ends -->

                        <div class="select_address col-xs-12 no-padding">

                            <div class="well col-xs-12 padding-md">
                                <div class="col-xs-12 col-sm-6 no-padding">
                                    <h4 class="normal_header">Select Delivery Address</h4>
                                </div> <!-- col-sm-4 ends -->
                                <div class="col-xs-12 col-sm-6 link no-padding">
                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#addAddressModal">+ Add New Address</a>
                                </div> <!-- col-sm-4 ends -->
                            </div>

                            <div class="bueno_form select_address_form">

                                <div class="col-xs-12 delivery_addresses">

                                    @foreach(auth()->user()->addresses->sortBy(function ($address, $key) {
                                    return  !in_array($address->id, auth()->user()->area_addresses()->pluck('id')->toArray());
                                    }) as $address)

                                        <div class="radio col-xs-12 col-md-4 user_address_row @if(!in_array($address->id, auth()->user()->area_addresses()->pluck('id')->toArray())){{ "disabled" }}@endif">
                                            <label class="left full_width">
			                                     <span class="txt"><strong class="text-uppercase">{{ $address->address_name }}</strong>
			                                         <p class="normal_para">{{ $address->address }}</p></span>
                                                <input type="radio" name="address_id" value="{{ $address->id }}" class="inputradio" id="radio1">
                                                <span class="check_style left"></span>
                                            </label>
                                        </div> <!-- radio ends -->
                                    @endforeach
                                        <input type="hidden" name="area_id" value="{{ session('area_id') }}">
                                    @if(auth()->user()->addresses->count() == 0)
                                            <div class="col-xs-12 placeholder_message address_placeholder_message">
                                                <h4 class="normal_header"><i class="ion-android-alert paddingright-xs"></i>Please add an address to continue.</h4>
                                            </div> <!-- placeholder_message ends -->@endif


                                </div> <!-- delivery_addresses ends -->

                            </div> <!-- select_address_form ends -->

                        </div> <!-- select_address ends -->

                        <div class="well col-xs-12 padding-md no-marginbottom">
                            <h4 class="normal_header">Checkout</h4>
                        </div>

                        <div class="table-responsive cart_table_responsive col-xs-12 no-padding marginbottom-lg">
                            <table class="table table-bordered cart_table">
                                <tbody>
                                <tr>
                                    <td class="product_delivery">
                                         {{-- <h4 class="heading marginbottom-md">Delivery Instructions</h4>
                                       <div class="check_options">
                                            <div class="checkbox">
                                                <label class="left">
                                                    <span class="txt">Please do not call for directions</span>
                                                    <input type="checkbox" name="instruction" class="inputcheckbox instruction_checkbox" value="Please do not call for directions">
                                                    <span class="check_style left"></span>
                                                </label>
                                            </div> <!-- checkbox ends -->
                                            <div class="checkbox">
                                                <label class="left">
                                                    <span class="txt">Please ask the delivery boy to call for directions</span>
                                                    <input type="checkbox" name="instruction" class="inputcheckbox instruction_checkbox" value="Please ask the delivery boy to call for directions">
                                                    <span class="check_style left"></span>
                                                </label>
                                            </div> <!-- checkbox ends -->
                                            <div class="checkbox">
                                                <label class="left">
                                                    <span class="txt">Please call on reaching and do not ring the doorbell</span>
                                                    <input type="checkbox" name="instruction" class="inputcheckbox instruction_checkbox" value="Please call on reaching and do not ring the doorbell">
                                                    <span class="check_style left"></span>
                                                </label>
                                            </div> <!-- checkbox ends -->
                                        </div> <!-- check_options ends --> --}}
                                        {{-- <p>Other : <em class="text-muted">(please specify)</em></p>
                                        <textarea name="instruction" id="" rows="5" class="bueno_inputtext black full_width marginbottom-sm instruction_textarea"></textarea> --}}
                                        {{--<input type="submit" class="btn btn-primary-outline full_width" value="save">--}}
                                        <div class="apply_coupon col-xs-12">
                                            <h4 class="heading marginbottom-md">Apply Coupon Code <em>(if applicable)</em></h4>
                                            <div class="form-group bueno_form_group">
                                                <div class="input-group bueno_inputgroup full_width icon_right apply_coupon_box">
                                                    <input type="text" name="coupon_code" class="bueno_inputtext apply_coupon_text black full_width" placeholder="Coupon Code" data-url="{{ route('checkout.coupon.apply') }}">
                                                        <span class="input-group-btn">
                                                          <button class="btn btn-default apply_coupon_button" type="button"><i class="ion-android-send"></i></button>
                                                        </span>
                                                </div>
                                                <span class="help-block coupon-error"></span>
                                                <div class="coupon-points">

                                                </div>
                                            </div> <!-- bueno_form_group ends -->

                                        </div> <!-- apply_coupon ends -->
                                        @if(auth()->user()->points)
                                        <div class="credits col-xs-12">
                                            <div class="checkbox">
                                                <label class="left large">
                                                    <span class="txt text-uppercase"><span class="bueno_usable_points_view">{{ calculateUsablePoints(auth()->user()->cartItems, auth()->user()->points) }}</span> Bueno Credits <span class="sub text-capitalize">You can redeeem 50% of your Order amount in one order.</span></span>
                                                    <input type="checkbox" name="redeem_points" class="inputcheckbox bueno_points_checkbox" data-points="{{ calculateUsablePoints(auth()->user()->cartItems, auth()->user()->points) }}" value="1">
                                                    <span class="check_style left"></span>
                                                </label>
                                            </div> <!-- checkbox ends -->
                                        </div> <!-- credits ends -->
                                        @endif
                                        <div class="donation col-xs-12">
                                            @foreach($ngos as $ngo)
                                                <div class="col-xs-12 no-padding">
                                                    <div class="col-xs-12 col-sm-4">
                                                        <img src="{{ route('photo.ngos', $ngo->display_image_url) }}" alt="" class="img-responsive">
                                                    </div>
                                                    <div class="col-xs-12 col-sm-8">
                                                        <h4 class="heading margintop-md">{{ $ngo->name }}</h4>
                                                    </div>
                                                </div> <!-- col-xs-12 ends -->
                                                <div class="col-xs-12 margintop-sm">
                                                    <div class="checkbox">
                                                        <label class="left">
                                                            <span class="txt">Donate ₹ {{ $ngo->default_donation_amount }} towards {{ $ngo->name }}</span>
                                                            <input type="checkbox" name="ngo_id" value="{{ $ngo->id }}" class="inputcheckbox donation_checkbox" data-name="{{ $ngo->name }}" data-amount="{{ $ngo->default_donation_amount }}">
                                                            <span class="check_style left"></span>
                                                        </label>
                                                    </div> <!-- checkbox ends -->
                                                </div> <!-- col-xs-12 ends -->
                                            @endforeach
                                        </div> <!-- donation ends -->
                                    </td> <!-- delivery_instructions ends -->
                                    <td class="product_extras">
                                        <div class="payment_method col-xs-12">
                                            <h4 class="heading marginbottom-md">Payment Method</h4>

                                            <div class="col-xs-12 no-padding">

                                                <div class="panel-group marginbottom-sm" id="payment_method_accordion" role="tablist" aria-multiselectable="true">
                                                    @if($payment_modes->where('id', 7)->where('status',1)->first() || $payment_modes->where('id', 8)->where('status',1)->first() )
                                                    <div class="panel dashed panel-default">
                                                        <div class="panel-heading" role="tab" id="titlePayViaCard">
                                                            <h4 class="panel-title">
                                                                <a role="button" class="arrow_down full_width" data-toggle="collapse" data-parent="#payment_method_accordion" href="#collapsePayViaCard" aria-expanded="true" aria-controls="collapsePayViaCard">
                                                                    Pay via Debit/ Credit / Net Banking / etc.
                                                                </a>
                                                            </h4>
                                                        </div> <!-- panel-heading ends -->
                                                        <div id="collapsePayViaCard" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="titlePayViaCard">
                                                            <div class="panel-body">
                                                                <div class="radio_options">
                                                                    @if($payment_modes->where('id', 7)->where('status',1)->first())
                                                                    <div class="radio">
                                                                        <label class="left">
                                                                            <span class="txt">Credit Card / Debit Card / Net Banking</span>
                                                                            <input type="radio" name="payment_mode_id" value="7" class="inputradio" checked id="radio1">
                                                                            <span class="check_style left"></span>
                                                                        </label>
                                                                    </div> <!-- radio ends -->
                                                                    @endif
                                                                        @if($payment_modes->where('id', 8)->where('status',1)->first())
                                                                            <div class="radio">
                                                                                <label class="left">
                                                                                    <span class="txt">{{ $payment_modes->where('id', 8)->where('status',1)->first()->name }}</span>
                                                                                    <input type="radio" name="payment_mode_id" value="8" class="inputradio" checked id="radio1">
                                                                                    <span class="check_style left"></span>
                                                                                </label>
                                                                            </div> <!-- radio ends -->
                                                                        @endif
                                                                </div> <!-- raadio_options ends -->
                                                            </div> <!-- panel-body ends -->
                                                        </div> <!-- panel-collapse ends -->
                                                    </div> <!-- panel ends -->
                                                    @endif
                                                    @if($payment_modes->where('id', 1)->where('status',1)->first() || $payment_modes->where('id', 2)->where('status', 1)->first() || $payment_modes->where('id', 3)->where('status', 1)->first() || $payment_modes->where('id', 5)->where('status', 1)->first())
                                                            <div class="panel dashed panel-default">
                                                                <div class="panel-heading" role="tab" id="titlePayViaWallet">
                                                                    <h4 class="panel-title">
                                                                        <a role="button" class="arrow_down collapsed" data-toggle="collapse" data-parent="#payment_method_accordion" href="#collapsePayViaWallet" aria-expanded="true" aria-controls="collapsePayViaWallet" class="full_width">
                                                                            Pay via Online Wallets / COD
                                                                        </a>
                                                                    </h4>
                                                                </div> <!-- panel-heading ends -->
                                                                <div id="collapsePayViaWallet" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="titlePayViaWallet">
                                                                    <div class="panel-body">
                                                                        <div class="radio_options">
                                                                            @if($payment_modes->where('id', 1)->where('status',1)->first())
                                                                            <div class="radio">
                                                                                <label class="left">
                                                                                    <span class="txt">{{ $payment_modes->where('id', 1)->where('status',1)->first()->name }}</span>
                                                                                    <input type="radio" name="payment_mode_id" value="1" class="inputradio" id="radio2">
                                                                                    <span class="check_style left"></span>
                                                                                </label>
                                                                            </div> <!-- radio ends -->
                                                                            @endif
                                                                                @if($payment_modes->where('id', 5)->where('status',1)->first())
                                                                            <div class="radio">
                                                                                <label class="left">
                                                                                    <span class="txt">{{ $payment_modes->where('id', 5)->where('status',1)->first()->name }}</span>
                                                                                    <input type="radio" name="payment_mode_id" value="5" class="inputradio" id="radio1">
                                                                                    <span class="check_style left"></span>
                                                                                </label>
                                                                            </div> <!-- radio ends -->
                                                                            @endif
                                                                                    @if($payment_modes->where('id', 3)->where('status',1)->first())
                                                                                        <div class="radio">
                                                                                            <label class="left">
                                                                                                <span class="txt">{{ $payment_modes->where('id', 3)->where('status',1)->first()->name }}</span>
                                                                                                <input type="radio" name="payment_mode_id" value="3" class="inputradio" id="radio1">
                                                                                                <span class="check_style left"></span>
                                                                                            </label>
                                                                                        </div> <!-- radio ends -->
                                                                                @endif
                                                                                @if($payment_modes->where('id', 2)->where('status',1)->first())
                                                                            <div class="radio">
                                                                                <label class="left">
                                                                                    <span class="txt">{{ $payment_modes->where('id', 2)->where('status',1)->first()->name }}</span>
                                                                                    <input type="radio" name="payment_mode_id" value="2" class="inputradio cod_payment" id="radio1">
                                                                                    <span class="check_style left"></span>
                                                                                </label>
                                                                            </div> <!-- radio ends -->
                                                                                @endif

                                                                        </div> <!-- raadio_options ends -->
                                                                    </div> <!-- panel-body ends -->
                                                                </div> <!-- panel-collapse ends -->
                                                            </div> <!-- panel ends -->
                                                        @endif
                                                </div> <!-- panel-group ends -->

                                            </div> <!-- col-xs-12 ends -->

                                        </div> <!-- payment_method ends -->

                                    </td> <!-- product_extras ends -->
                                    <td class="product_total">
                                        <div class="full_width vat_amount">
                                        @if($kitchen->vat)
                                            <p class="total_heading">VAT</p>
                                            <input type="hidden" name="vat" class="vat_input" value="{{ $kitchen->vat }}"/>
                                            <p class="total_content vat_amount_text">₹ <span>{{ applyTax(total_cart_amount(auth()->user()->cartItems), $kitchen->vat) }}</span></p>
                                        @endif
                                        </div>

                                        @if($kitchen->delivery_charge)
                                        <div class="full_width delivery_charge_amount">
                                            <p class="total_heading">Delivery Charge</p>
                                            <input type="hidden" name="delivery_charge" class="delivery_charge_input" value="{{ $kitchen->delivery_charge }}"/>
                                            <p class="total_content delivery_charge_text">₹ <span>{{  $kitchen->delivery_charge }}</span></p>
                                        </div>
                                        @endif
                                        @if($kitchen->packaging_charge)
                                            <div class="full_width packaging_charge_amount">
                                                <p class="total_heading">Packaging Charge</p>
                                                <input type="hidden" name="packaging_charge" class="packaging_charge_input" value="{{ $kitchen->packaging_charge }}"/>
                                                <p class="total_content packaging_charge_text">₹ <span>{{  $kitchen->packaging_charge }}</span></p>
                                            </div>
                                        @endif
                                        @if($kitchen->service_tax)
                                        <div class="full_width service_tax_amount">
                                            <p class="total_heading">Service Tax</p>
                                            <input type="hidden" name="service_tax" class="service_tax_input" value="{{ $kitchen->service_tax }}"/>
                                            <p class="total_content service_tax_text">₹ <span>{{ applyTax(total_cart_amount(auth()->user()->cartItems), $kitchen->service_tax) }}</span></p>
                                        </div>
                                        @endif
                                        @if($kitchen->service_charge)
                                        <div class="full_width service_charge_amount">
                                            <p class="total_heading">Service Charge</p>
                                            <input type="hidden" name="service_charge" class="service_charge_input" value="{{ $kitchen->service_charge }}"/>
                                            <p class="total_content service_charge_text">₹ <span>{{  $kitchen->service_charge }}</span></p>
                                        </div>
                                        @endif
                                        <div class="full_width total_amount">
                                            <input type="hidden" name="amount" class="checkout-amount-input" value="{{ total_cart_amount(auth()->user()->cartItems) }}"/>
                                            <p class="total_heading">Total</p>
                                            <p class="total_content">₹ <span class="checkout-total-amount">{{ applyTax(total_cart_amount(auth()->user()->cartItems), $kitchen->vat) + applyTax(total_cart_amount(auth()->user()->cartItems), $kitchen->service_tax) + total_cart_amount(auth()->user()->cartItems) + $kitchen->service_charge  + $kitchen->delivery_charge + $kitchen->packaging_charge }}</span></p>
                                        </div>
                                        <div class="full_width">
                                            <button  class="btn btn-primary big_btn full_width @if($checkout_errors){{ "disabled" }}@endif" id="payment_button">Place Order</button>
                                            <div class="checkout-error-container">
                                                <div class="checkout-error">

                                                </div>
                                            </div>
                                            <a href="{{ route('users.saved_items.get') }}" class="btn btn-primary-outline big_btn full_width marginbottom-md">View Saved Items</a>
                                            <a href="{{ route('pages.index') }}" class="btn btn-primary-outline big_btn full_width">Continue Shopping</a>
                                        </div>
                                    </td> <!-- product_total_label ends -->
                                </tr>
                                </tbody>
                            </table> <!-- cart_table ends -->
                        </div> <!-- table-responsive ends -->

                    </div> <!-- col-xs-12 ends -->
                 @else
                    <div class="col-xs-12 placeholder_message">
                        <h4 class="normal_header"><i class="ion-android-alert paddingright-xs"></i>Your cart is empty ! </h4>
                    </div> <!-- placeholder_message ends -->
                @endif
            </form>
        </div> <!-- row ends -->
    </div> <!-- container ends -->

</section> <!-- cart_sec ends -->

<div id="addAddressModal" class="black_modal modal add_address_modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="ion-ios-close-empty"></i></span></button>
                <div class="header_icon icon_circle">
                    <i class="ion-ios-location"></i>
                </div> <!-- header_icon ends -->
                <h4 class="modal-title text-uppercase">Add Address</h4>
                <hr class="white no-marginbottom" />
            </div> <!-- modal-header ends -->
            <div class="modal-body text-center clearfix">
                <div class="modal-errors-list add-address-errors-list">

                </div>
                <form action="{{  route('users.address.new.post') }}" method="POST" class="bueno_form delivery_address delivery_address_modal col-xs-12 margintop-md">
                    {{ csrf_field() }}
                    <div class="col-xs-12 no-padding">
                        <input type="hidden" value="{{ auth()->user()->id }}" name="user_id"/>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group bueno_form_group">
                                    <input type="text" class="form-control bueno_inputtext black" name="address_name" id="title" placeholder="Title" required>
                                </div> <!-- bueno_form_group ends -->
                                <div class="form-group bueno_form_group">
                                    <textarea class="form-control bueno_inputtext black" id="add1" name="address" placeholder="Address" required></textarea>
                                </div> <!-- bueno_form_group ends -->
                                <div class="form-group bueno_form_group">
                                    <input type="number" class="form-control bueno_inputtext black" id="pincode" name="pincode" placeholder="Pincode" required>
                                </div> <!-- bueno_form_group ends -->
                            </div> <!-- col-xs-12 ends -->
                        </div> <!-- row ends -->

                        <div class="row">
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group bueno_form_group">
                                    <label class="bueno_select no_caret form">
                                        <input type="hidden" name="area_id" value="{{ session('area_id') }}"/>
                                        <select name="" id="gender" class="full_width no-marginbottom bueno_select2" disabled>
                                                <option value="{{ $area->id }}" {{ "selected" }}>{{ $area->name }}</option>
                                        </select>
                                    </label> <!-- bueno_select ends -->
                                </div> <!-- bueno_form_group ends -->
                            </div> <!-- col-xs-12 ends -->
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group bueno_form_group">
                                    <input type="submit" class="btn form btn-warning full_width" value="Save Details">
                                </div> <!-- bueno_form_group ends -->
                            </div> <!-- col-xs-12 ends -->
                        </div> <!-- row ends -->

                    </div> <!-- col-xs-12 ends -->
                </form> <!--  delivery_address form ends -->
            </div> <!-- modal-body ends -->
        </div> <!-- modal-content ends -->
    </div> <!-- modal-dialog ends -->
</div> <!-- modal ends -->

<script>
    fbq('track', 'InitiateCheckout');
</script>

@endsection
