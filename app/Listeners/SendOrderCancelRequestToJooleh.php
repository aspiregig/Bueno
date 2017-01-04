<?php

namespace App\Listeners;

use App\Events\OrderWasCancelled;
use Bueno\Services\JoolehAPI as Jooleh;
use Bueno\Repositories\DbOrderRepository as OrderRepo;

class SendOrderCancelRequestToJooleh
{

    protected  $jooleh,$orderRepo;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Jooleh $jooleh,OrderRepo $orderRepo)
    {
        $this->jooleh = $jooleh;
        $this->orderRepo = $orderRepo;
    }

    /**
     * Handle the event.
     *
     * @param  OrderWasCancelled  $event
     * @return void
     */
    public function handle(OrderWasCancelled $event)
    {
        $response = $this->jooleh->cancelOrder($event->order);
        $response = $response->getBody()->getContents();
        $this->orderRepo->updateJoolehOrder($response);
    }
}
