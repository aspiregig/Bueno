
<h2>Error</h2>

@if(Session::has('flash_notification.message'))
    {!!  Session::get('flash_notification.message') !!}
@endif