<?php

namespace App\Listeners;

use App\Events\OrderWasSettled;
use Bueno\Repositories\DbOrderRepository;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreditLoyaltyPoints
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(DbOrderRepository $orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }

    /**
     * Handle the event.
     *
     * @param  OrderWasSettled  $event
     * @return void
     */
    public function handle(OrderWasSettled $event)
    {
        $this->orderRepo->creditLoyality($event->order);
    }
}
