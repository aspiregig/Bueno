<div class="profile_dropdown dropdown">
    <a class="nav_link dropdown-toggle" id="profileMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
        Hi, {{ auth()->user()->first_name }}
            <span class="caret"></span>
    </a>
        <ul class="dropdown-menu" aria-labelledby="profileMenu">
            <a href="{{ route('users.account.get')}}"><li>My Account</li></a>
            <a href="{{ route('users.loyalty.get')}}"><li>Bueno Rewards</li></a>
            <a href="{{ route('users.orders.get')}}"><li>Order History</li></a>
            <a href="{{ route('users.saved_items.get') }}"><li>Saved Items</li></a>
            <a href="{{ route('users.cart.get')}}"><li>Cart</li></a>
        </ul> <!-- dropdown-menu ends -->
</div> <!-- profile_dropdown ends -->