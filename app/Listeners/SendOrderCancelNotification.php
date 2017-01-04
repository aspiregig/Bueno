<?php

namespace App\Listeners;

use App\Events\OrderWasCancelled;
use App\Models\Order;
use Bueno\Services\SmsAPI;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderCancelNotification
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
     * @param  OrderWasCancelled  $event
     * @return void
     */
    public function handle(OrderWasCancelled $event)
    {
      $this->sms->send($event->order->user->phone, "Hi " . $event->order->user->full_name . ", we regret to inform you that your order #". $event->order->order_no."  has been cancelled. Contact Bueno Care at 01139586767 or info@bueno.kitchen. Cancellation reason: " . $event->order->cancelReason->reason);
    }
}
