<?php

namespace App\Listeners;

use Bueno\Services\TorqusApi as Torqus;
use App\Events\SyncStockWithTorqusEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Bueno\Repositories\ItemRepository as ItemRepo;
use Bueno\Loggers\ApiLogger as Logger;
class SyncStock
{

  protected  $itemRepo,$torqus;

  /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(ItemRepo $itemRepo,Torqus $torqus,Logger $logger)
    {
      $this->itemRepo = $itemRepo;
      $this->torqus = $torqus;
      $this->logger = $logger;
    }

    /**
     * Handle the event.
     *
     * @param  SyncStockWithTorqusEvent  $event
     * @return void
     */
    public function handle(SyncStockWithTorqusEvent $event)
    {
      $kitchens = $this->itemRepo->getAllKitchens();
      foreach($kitchens as $kitchen)
      {
        $response = $this->torqus->getAllDishes($kitchen->torqus_spoke_id);
        $response = $response->getBody()->getContents();
        $response = json_decode($response);
        $this->itemRepo->updateStockByTorqus($response,$kitchen->id);
      }
    }
}
