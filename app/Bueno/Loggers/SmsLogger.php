<?php namespace Bueno\Loggers;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class SmsLogger {

  /**
   *  save response to the smslog.log file
   *
   * @param $response
   */
  public function log($message)
  {
    $view_log = new Logger('SMS Logs');

    $view_log->pushHandler(new StreamHandler(storage_path() . '/logs/smslog.log', Logger::INFO));

    $view_log->addInfo($message);
  }

}