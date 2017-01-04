<em><a href="{{ route('users.account.get') }}" class="@if(Route::currentRouteName() == 'users.account.get'){{"active"}}@endif">My Account</a></em>
<em><a href="{{ route('users.loyalty.get') }}" class="@if(Route::currentRouteName() == 'users.loyalty.get'){{"active"}}@endif">Bueno Loyalty Program</a></em>
<em><a href="{{ route('users.orders.get') }}" class="@if(Route::currentRouteName() == 'users.orders.get'){{"active"}}@endif">Order History</a></em>
<em><a href="{{ route('users.logout') }}">Sign Out</a></em></small>