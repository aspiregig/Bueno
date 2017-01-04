<?php

namespace App\Listeners;

use App\Events\OrderWasSettled;
use Bueno\Repositories\DbUserRepository;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateUserMembership
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(DbUserRepository $userRepo)
    {
      $this->userRepo = $userRepo;
    }

    /**
     * Handle the event.
     *
     * @param  OrderWasSettled  $event
     * @return void
     */
    public function handle(OrderWasSettled $event)
    {
      $this->userRepo->assignMembershipToUser($event->order->user);
    }
}
