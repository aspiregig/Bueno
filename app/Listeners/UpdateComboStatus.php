<?php

namespace App\Listeners;

use App\Events\MealWasDisabled;
use Bueno\Repositories\ItemRepository;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateComboStatus
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(ItemRepository $itemRepo)
    {
      $this->itemRepo = $itemRepo;
    }

    /**
     * Handle the event.
     *
     * @param  MealWasDisabled  $event
     * @return void
     */
    public function handle(MealWasDisabled $event)
    {
        $this->itemRepo->mealDisabled($event->meal);
    }
}
