<?php

namespace App\Listeners;

use Exception;
use App\Events\OrderWasCreatedByUser;
use Bueno\Loggers\EmailLogger;
use Bueno\Mailers\UserMailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderEmailNotificationToUser
{

    protected $logger;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(UserMailer $mailer,EmailLogger $logger)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    /**
     * Handle the event.
     *
     * @param  OrderWasCreatedByUser  $event
     * @return void
     */
    public function handle(OrderWasCreatedByUser $event)
    {
        if($event->order->user->email)
        {
            try {
                $this->mailer->orderConfirmed($event->order);
            } catch (Exception $e) {
                $this->logger->log('Error while Sending mail To : (order_no : '.$event->order->order_no.')'.$event->order->user->full_name);
    
            }
          
        }
    }
}
