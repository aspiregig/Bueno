<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ReferralSuccess extends Event
{
    use SerializesModels;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($phone,$user,$points)
    {
        $this->phone = $phone;
        $this->user = $user;
        $this->points = $points;
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
