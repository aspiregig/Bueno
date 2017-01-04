<?php

namespace App\Listeners;

use App\Events\StockWasUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateItemStatus
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  StockWasUpdated  $event
     * @return void
     */
    public function handle(StockWasUpdated $event)
    {
        //
    }
}
