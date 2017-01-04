<?php

namespace App\Listeners;

use App\Events\OrderWasCancelled;
use Bueno\Repositories\DbOrderRepository;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReStockOrderItems
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
     * @param  OrderWasCancelled  $event
     * @return void
     */
    public function handle(OrderWasCancelled $event)
    {
      $this->orderRepo->reStockCancelledOrderItems($event->order);
    }
}
