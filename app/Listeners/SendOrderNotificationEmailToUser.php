<?php

namespace App\Listeners;

use Exception;
use App\Events\OrderWasCreated;
use Bueno\Mailers\UserMailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderNotificationEmailToUser
{
    /**
     * Create the event listener.
     *
     * @return void
     */
  public function __construct(UserMailer $mailer)
  {
    $this->mailer = $mailer;
  }

    /**
     * Handle the event.
     *
     * @param  OrderWasCreated  $event
     * @return void
     */
    public function handle(OrderWasCreated $event)
    {
      if($event->order->user->email)
        {
                $this->mailer->orderConfirmed($event->order);
        }
    }
}
