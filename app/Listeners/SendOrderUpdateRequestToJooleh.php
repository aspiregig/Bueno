<?php

namespace App\Listeners;

use App\Events\OrderWasDispatched;
use Bueno\Services\JoolehAPI as Jooleh;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Bueno\Repositories\DbOrderRepository as OrderRepo;

class SendOrderUpdateRequestToJooleh
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
   * @param  OrderWasDispatched  $event
   * @return void
   */
  public function handle(OrderWasDispatched $event)
  {
    $response = $this->jooleh->updateOrder($event->order);
    $response = $response->getBody()->getContents();
    $this->orderRepo->updateJoolehOrder($response);
  }
}
