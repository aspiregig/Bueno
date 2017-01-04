<?php

namespace App\Listeners;

use App\Events\ReferralSuccess;
use Bueno\Services\SmsAPI;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendSmsOnReferralSuccess
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(SmsAPI $smsAPI)
    {
        $this->smsAPI = $smsAPI;
    }

    /**
     * Handle the event.
     *
     * @param  ReferralSuccess  $event
     * @return void
     */
    public function handle(ReferralSuccess $event)
    {
        $message = "Your friend ".$event->user." placed his first order on Bueno using your referral code. Your account has been credited with ".$event->points." Bueno credits";
        $this->smsAPI->send($event->phone,$message);
    }
}
