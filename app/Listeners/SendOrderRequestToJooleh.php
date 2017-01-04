<?php

namespace App\Listeners;

use Bueno\Services\JoolehAPI as Jooleh;
use App\Events\OrderStatusWasUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Bueno\Repositories\DbOrderRepository as OrderRepo;

class SendOrderRequestToJooleh
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
   * @param  OrderWasCreated  $event
   * @return void
   */
  public function handle(OrderStatusWasUpdated $event)
  {
    $response = $this->jooleh->getOrderDetails($event->order);
    if($response && $response->getBody())
    {
      $response = $response->getBody()->getContents();
      $this->orderRepo->updateJoolehOrder($response);
    }
  }
}
