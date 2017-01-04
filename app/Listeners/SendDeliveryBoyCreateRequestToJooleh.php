<?php

namespace App\Listeners;

use App\Events\DeliveryBoyWasCreated;
use Bueno\Services\JoolehAPI as Jooleh;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Bueno\Repositories\DbKitchenRepository as KitchenRepo;

class SendDeliveryBoyCreateRequestToJooleh
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
     * @param  DeliveryBoyWasCreated  $event
     * @return void
     */
    public function handle(DeliveryBoyWasCreated $event)
    {
      $response = $this->jooleh->createDeliveryBoy($event->delivery_boy);
      $response = $response->getBody()->getContents();
      $this->kitchenRepo->newJoolehDeliveryBoy($event->delivery_boy->id,$response);
    }
}
