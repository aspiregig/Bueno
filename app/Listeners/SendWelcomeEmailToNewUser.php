<?php

namespace App\Listeners;

use App\Events\UserWasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Bueno\Mailers\UserMailer as Mailer;

class SendWelcomeEmailToNewUser
{
    protected $mailer;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Handle the event.
     *
     * @param  UserWasCreated  $event
     * @return void
     */
    public function handle(UserWasCreated $event)
    {
        if($event->user->email)
        {
            try {
                $this->mailer->welcome($event->user);
            } catch (Exception $e) {
                $logger->log('Error while Sending mail To : (user_id : '.$event->user->id.')'.$event->user->full_name);
    
            }
          
        }
        
    }
}
