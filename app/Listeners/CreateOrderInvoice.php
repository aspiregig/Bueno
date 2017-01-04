<?php

namespace App\Listeners;

use App\Events\OrderWasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Bueno\Repositories\DbOrderRepository as OrderRepo;

class CreateOrderInvoice
{
  protected  $orderRepo;
  /**
   * Create the event listener.
   *
   * @return void
   */
  public function __construct(OrderRepo $orderRepo)
  {
    $this->orderRepo = $orderRepo;
  }

    /**
     * Handle the event.
     *
     * @param  OrderWasCreated  $event
     * @return void
     */
    public function handle(OrderWasCreated $event)
    {
//        $this->orderRepo->createOrderInvoice($event->order);
    }
}
