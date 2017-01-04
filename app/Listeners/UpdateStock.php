<?php

namespace App\Listeners;

use App\Events\OrderWasCreatedByUser;
use Bueno\Repositories\DbOrderRepository;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateStock
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
     * @param  OrderWasCreatedByUser  $event
     * @return void
     */
    public function handle(OrderWasCreatedByUser $event)
    {
        foreach($event->order->orderItems as $item)
        {
          $this->orderRepo->updateStock($event->order->kitchen_id, $item->id, $item->pivot->quantity);
        }
    }
}
