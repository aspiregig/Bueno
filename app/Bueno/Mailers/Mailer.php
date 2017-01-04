<?php namespace Bueno\Mailers;

use Exception;
use Mail;
use Bueno\Loggers\EmailLogger as Logger;

abstract class Mailer{

	public function sendTo($email, $subject, $view, $data, $attachment = null)
  {
     if($email)
      if(env('APP_ENV') == 'production')
      {
          $mail = Mail::queue($view, $data, function ($message) use ($email, $subject,$attachment) {

          if (is_array($email)) {
            foreach ($email as $e) {
              $message->to($e);
            }
          } else {
            $message->to($email);
          }          

           if($attachment) {
            $message->attach($attachment, array('as' => 'orders-export.csv'));
          }

          $message->subject($subject)
              ->bcc(config('bueno.site.bcc_email'));
        });
      }
      else
      {
          try{
        $mail = Mail::queue($view, $data, function ($message) use ($email, $subject,$attachment) {

            $message->to('pankajb@gmail.com');
                     if($attachment) {
                      dd($attachment);
            $message->attach($attachment, array('as' => 'orders-export.csv'));
          }

          $message->subject($subject);
        });
      }
      catch(Exception $e)
      {
        echo $e->getMessage();   
      }
    }
  }
}