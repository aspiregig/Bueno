<?php

namespace App\Listeners;

use App\Events\DeliveryBoyWasUpdated;
use Bueno\Services\JoolehAPI as Jooleh;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Bueno\Repositories\DbKitchenRepository as KitchenRepo;

class SendDeliveryBoyUpdateRequestToJooleh
{

  protected $jooleh,$kitchenRepo;
  /**
   * Create the event listener.
   *
   * @return void
   */
  public function __construct(Jooleh $jooleh,KitchenRepo $kitchenRepo)
  {
    $this->jooleh = $jooleh;
    $this->kitchenRepo = $kitchenRepo;
  }

  /**
   * Handle the event.
   *
   * @param  DeliveryBoyWasUpdated  $event
   * @return void
   */
  public function handle(DeliveryBoyWasUpdated $event)
  {
     $this->jooleh->updateDeliveryBoy($event->delivery_boy);
  }
}
