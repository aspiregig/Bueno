<?php namespace Bueno\Notifications;

use Illuminate\Session\Store;

class FlashNotifier {

  private $session;

  function __construct(Store $session) 
  {
    $this->session = $session;
  }

  /**
   * fluses the flash_notification key from session
   */
  public function clear()
  {
    $this->session->forget('flash_notification.message');
  }
  
  public function info($message)
  {
    $this->message($message, 'info');
  }

  public function success($message)
  {
    $this->message($message, 'success');
  }

  public function danger($message)
  {
    $this->message($message, 'danger');
  }

  public function message($message, $type = 'info')
  {
    $this->session->flash('flash_notification.message', $message);

    $this->session->flash('flash_notification.type', $type);
  }
}