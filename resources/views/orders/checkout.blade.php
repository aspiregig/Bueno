

@if(auth()->user()->cartItems->count())
    @include('admin.partials.flash')
    @include('admin.partials.errors')
    <form action="{{ route('checkout.post') }}" method="POST">

        <h5>Address</h5>
        <ul>
            @foreach(auth()->user()->addresses->where('area_id', (Integer) session('area')) as $address)
                <label for="">
                    <input type="radio" name="address_id" value="{{ $address->id }}"/>
                    <span>{{ $address->address_name }}</span>
                </label>
            @endforeach
        </ul>
        <ul>
            @foreach( auth()->user()->cartItems as $cart_item)
                <li>{{ $cart_item->item->itemable->name }}, {{ $cart_item->item->itemable->discount_price }}
                    <input type="hidden" name="cart_id" value="{{ $cart_item->id }}"/>
                    <input type="hidden" name="items[{{ $cart_item->item_id }}][quantity]"  value="{{ $cart_item->quantity }}"/>
                    <input type="hidden" name="items[{{ $cart_item->item_id }}][price]"  value="{{ $cart_item->item->itemable->discount_price }}"/>
                </li>
            @endforeach

            <select name="payment_mode_id">
                @foreach(config('bueno.payment_modes') as $key  => $payment_mode)
                    <option value="{{ $key }}">{{ $payment_mode }}</option>
                @endforeach
            </select>
            <textarea name="instruction" id="instruction" cols="30" rows="10"></textarea>
        </ul>
        <input type="text" placeholder="Coupon Code" name="code" class="coupon-input"/>
        <input type="hidden" name="coupon_id" value="" id="coupon_id"/>
        <input type="text" name="amount" id="total_amount" value="{{ total_cart_amount(auth()->user()->cartItems) }}"/>
        <button id="apply-coupon" data-url="{{ route('checkout.coupon.apply') }}">Apply Coupon</button>

        {{ csrf_field() }}
        <input type="submit" value="Submit"/>
    </form>

@else
    <p>Please Add Items to your cart</p>
@endif

@include('partials/js_includes')
