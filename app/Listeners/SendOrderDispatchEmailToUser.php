<?php

namespace App\Listeners;

use App\Events\OrderWasDispatched;
use Bueno\Mailers\UserMailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderDispatchEmailToUser
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(UserMailer $userMailer)
    {
        $this->userMailer = $userMailer;
    }

    /**
     * Handle the event.
     *
     * @param  OrderWasDispatched  $event
     * @return void
     */
    public function handle(OrderWasDispatched $event)
    {
      if($event->order->user->email)
      {
        $this->userMailer->orderDispatched($event->order);
      }
    }
}
