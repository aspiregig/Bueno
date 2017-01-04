<?php

namespace App\Events;

use App\Events\Event;
use App\Models\DeliveryBoy;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class DeliveryBoyWasUpdated extends Event
{
  use SerializesModels;

  public $delivery_boy;

  /**
   * Create a new event instance.
   *
   * @return void
   */
  public function __construct(DeliveryBoy $delivery_boy)
  {
    $this->delivery_boy = $delivery_boy;
  }

  /**
   * Get the channels the event should be broadcast on.
   *
   * @return array
   */
  public function broadcastOn()
  {
    return [];
  }
}
