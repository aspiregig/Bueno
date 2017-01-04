<?php

namespace App\Listeners;

use App\Events\UserWasCreated;
use Bueno\Repositories\DbUserRepository;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AssignGroupRoleToUser
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
     * @param  UserWasCreated  $event
     * @return void
     */
    public function handle(UserWasCreated $event)
    {
      $this->userRepo->assignGroupToUser($event->user);
    }
}
