<?php

namespace App\Listeners;

use App\Events\UserWasRegistered;
use Bueno\Services\SmsAPI;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Bueno\Repositories\DbUserRepository;

class SendOTPVerificationToUser
{
    /**
     * Create the event listener.
     *
     * @return void
     */

    protected $sms;

    public function __construct(SmsAPI $sms, DbUserRepository $userRepo)
    {
      $this->sms = $sms;

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
        $this->userRepo->sendVerifyOTPSMS($event->user->phone, $event->user->mobile_otp);
    }
}
