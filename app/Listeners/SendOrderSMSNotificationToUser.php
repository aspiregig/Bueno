<?php

namespace App\Listeners;

use App\Events\OrderWasCreatedByUser;
use Bueno\Services\SmsAPI;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderSMSNotificationToUser
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(SmsAPI $sms)
    {
        $this->sms = $sms;
    }

    /**
     * Handle the event.
     *
     * @param  OrderWasCreatedByUser  $event
     * @return void
     */
    public function handle(OrderWasCreatedByUser $event)
    {
      $order = $event->order;

      $message = "Hello ".$order->user->full_name.", Thank you for placing your order with Bueno. Your order number is ".$order->order_no.". Goodness will be delivered at your doorstep shortly.";

      $this->sms->send($event->order->user->phone, $message);
    }
}
