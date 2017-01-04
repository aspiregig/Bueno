<?php

namespace App\Listeners;

use App\Events\OrderWasCreated;
use Bueno\Services\SmsAPI;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendSmsOrderCreateNotitficationRequest
{

    protected $smsAPI;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(SmsAPI $smsAPI)
    {
        $this->smsAPI = $smsAPI;
    }

    /**
     * Handle the event.
     *
     * @param  OrderWasCreated  $event
     * @return void
     */
    public function handle(OrderWasCreated $event)
    {
        $order = $event->order;
        $message = "Hello ".$order->user->full_name.", Thank you for placing your order with Bueno. Your order number is BUENO".$order->id.". Goodness will be delivered at your doorstep shortly.";
        $this->smsAPI->send($order->user->phone,$message);
    }
}
