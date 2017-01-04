<?php

namespace App\Listeners;

use App\Events\UserWasRegistered;
use Bueno\Repositories\DbUserRepository;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GenerateReferralCouponOfUser
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
     * @param  UserWasRegistered  $event
     * @return void
     */
    public function handle(UserWasRegistered $event)
    {
        $this->userRepo->createUserReferralCode($event->user);
    }
}
