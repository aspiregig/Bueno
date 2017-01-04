<div class="user_dropdown dropdown">
    <a class="dropdown-toggle" id="mobileNavDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
        Hi, {{ auth()->user()->first_name }}
        <span class="caret"></span>
    </a>
    <ul class="dropdown-menu" aria-labelledby="mobileNavDropdown">
        <li><a href="{{ route('users.account.get')}}">My Account</a></li>
        <li><a href="{{ route('users.orders.get')}}">Order History</a></li>
        <li><a href="{{ route('users.saved_items.get') }}">Saved Items</a></li>
        <li><a href="{{ route('users.cart.get')}}">Cart</a></li>
    </ul> <!-- dropdown-menu ends -->
</div> <!-- profile_dropdown ends -->