  @if(Session::has('flash_notification.message'))
     <div class="alert alert-{{ Session::get('flash_notification.type') }} alert-dismissable" >
         <button type="button" class="close" data-dismiss="alert"
                 aria-hidden="true">
             &times;
         </button>
         {!!  Session::get('flash_notification.message') !!}
     </div>
  @endif