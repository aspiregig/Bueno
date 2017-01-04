<?php namespace Bueno\Loggers;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class DebugLogger {

  /**
   *  save response to the voiceotp.log file
   *
   * @param $response
   */
  public function log($message)
  {
    $view_log = new Logger('Debug Logs');

    $view_log->pushHandler(new StreamHandler(storage_path() . '/logs/debug.log', Logger::INFO));

    $view_log->addInfo($message);
  }

}