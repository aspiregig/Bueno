  @if(Session::has('flash_notification.message'))
     <div class="alert alert-{{ Session::get('flash_notification.type') }} alert-dismissable" >
         {!!  Session::get('flash_notification.message') !!}
     </div>
  @endif