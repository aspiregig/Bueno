<?php

namespace App\Listeners;

use App\Events\OrderWasDispatched;
use App\Models\Order;
use Bueno\Services\SmsAPI;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderDispatchSMSToUser
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
     * @param  OrderWasDispatched  $event
     * @return void
     */
    public function handle(OrderWasDispatched $event)
    {
        $this->sms->send($event->order->user_phone, "Hi " . $event->order->user->full_name . ", Your Bueno order No: ". $event->order->order_no." is dispatched and ".$event->order->deliveryBoy->full_name." (".$event->order->deliveryBoy->phone.") is arriving soon with your 'Bueno' meal. Call: ".config('bueno.site.phone')." Or Track at: http://place.url/here Powered by Sparse Labs");
    }
}
