@extends('layouts.master')

@section('content')

    @include('partials.locality_select')


    <section class="cart_sec saved_sec">

        <section class="title_sec white-bg">
            <div class="container more">
                <div class="row">
                    <div class="main-sec stick_lines col-xs-12">
                        <div class="col-sm-12 col-md-6 left-sec">
                            <h2 class="style_header_loud">Saved Items</h2>
                        </div> <!-- left-sec ends -->
                        <div class="col-sm-12 col-md-6 right-sec">
                        </div> <!-- right-sec ends -->
                    </div> <!-- col-xs-12 ends -->
                </div> <!-- row ends -->
            </div> <!-- container ends -->
        </section> <!-- title_sec ends -->

        <div class="container more">
            <div class="row saved_item_container">

                @if($items->count())
                    <form action="{{ route('users.saved_items.cart.post') }}" method="POST">
                        {{ csrf_field() }}
                    <div class="col-xs-12">

                        @include('partials.flash')
                        <div class="table-responsive cart_table_responsive">
                            <table class="table table-bordered cart_table no-marginbottom">
                                <thead>
                                <tr>
                                    <th>Item</th>
                                    <th class="text-center">Rate</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($items as $item)
                                <tr class="saved_item_row @if($item->stock() == 0){{ "disabled" }}@endif">
                                    <td class="product_detail">
                                        <input type="hidden" value="{{ $item->id }}" name="items[{{ $item->id }}][item_id]"/>
                                        <div class="sec_table">
                                            <div class="image sec_table_cell">
                                                <a href="{{ $item->item_url }}">
                                                    <div class="cart_page_item_image" style="background-image: url({{ $item->thumb_image_url }})"></div>
                                                </a>
                                            </div> <!-- image ends -->
                                            <div class="details sec_table_cell">
                                                <div class="details_holder">
                                                    <a href="{{ $item->item_url }}">
                                                        <h4 class="title">{{ $item->itemable->name }}</h4>
                                                    </a>
                                                    <div class="action_container col-xs-12">
                                                        <div class="btn_sec col-xs-6">
                                                                <input type="hidden" value="1" name="items[{{ $item->id }}][quantity]" class="item_quantity_select"/>
                                                                <a href="" class="btn btn-green icon marginbottom-sm add_saved_to_cart" data-token="{{ csrf_token() }}" data-id="{{ $item->id }}" data-user="{{ auth()->user()->id }}" data-url="{{ route('users.cart.post') }}" >Add to Cart<i class="ion-plus"></i></a><br />

                                                                <button  class="btn btn-red icon delete_from_saved" data-token="{{ csrf_token() }}" data-url="{{ route('users.saved_items.delete') }}" data-id="{{ $item->id }}">Remove<i class="ion-close"></i></button>

                                                        </div> <!-- btn_sec ends -->
                                                        @if($item->stock() == 0)
                                                        <div class="alert_sec col-xs-6">
                                                            <div class="bueno_form_group has-error">
                                                                <span class="help-block">This item is out of stock or<br /> it is not available in your area</span>
                                                            </div> <!-- bueno_form_group ends -->
                                                        </div> <!-- alert_sec ends -->
                                                        @endif
                                                    </div> <!-- action_container ends -->
                                                </div> <!-- details_holder ends -->
                                            </div> <!-- details ends -->
                                        </div> <!-- sec_table ends -->
                                    </td> <!-- product_detail ends -->
                                    <td class="product_rate">
                                        <span class="price">₹ {{ $item->itemable->discount_price }}</span>
                                    </td> <!-- product_rate ends -->
                                </tr>
                                @endforeach
                                </tbody>
                            </table> <!-- cart_table ends -->
                        </div> <!-- table-responsive ends -->

                        <div class="saved_actions col-xs-12">
                            <div class="pull-right">
                                <a href="{{ route('items.search.xprs-menu.get') }}" class="btn btn-primary-outline big_btn marginright-md">Continue Shopping</a>
                                <a  class="btn btn-primary big_btn add_all_saved @if(!$can_add){{ "disabled" }}@endif">Add all to Cart</a>
                            </div> <!-- pull-right ends -->
                        </div> <!-- saved_actions ends -->

                    </div> <!-- col-xs-12 ends -->
                </form>
                @else
                    <div class="col-xs-12 placeholder_message">
                        <h4 class="normal_header"><i class="ion-android-alert paddingright-xs"></i>Sorry, there are no results to show.</h4>
                    </div> <!-- placeholder_message ends -->
                @endif

            </div> <!-- row ends -->
        </div> <!-- container ends -->

    </section> <!-- cart_sec ends -->



@endsection