<?php

namespace App\Listeners;

use App\Events\OrderWasCreatedByUser;
use Bueno\Mailers\SiteMailer;
use Bueno\Repositories\ItemRepository;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CheckIfItemsAreInLowStock
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(ItemRepository $itemRepo, SiteMailer $mailer)
    {
        $this->itemRepo = $itemRepo;

        $this->mailer = $mailer;
    }

    /**
     * Handle the event.
     *
     * @param  OrderWasCreatedByUser  $event
     * @return void
     */
    public function handle(OrderWasCreatedByUser $event)
    {
      $low_stocks  = $this->itemRepo->getLowStockItems();

      if($low_stocks->count() > 0)
      {
        $this->mailer->lowStockAlert($low_stocks);
      }


    }
}
