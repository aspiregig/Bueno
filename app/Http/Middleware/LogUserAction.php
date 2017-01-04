<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Bueno\Loggers\UserActionsLogger;
use Illuminate\Contracts\Auth\Guard;

class LogUserAction
{
  /**
   * The Guard implementation.
   *
   * @var Guard
   */
  protected $auth;

  /**
   * Create a new filter instance.
   *
   * @param  Guard  $auth
   * @return void
   */
  public function __construct(Guard $auth, UserActionsLogger $logger)
  {
    $this->auth = $auth;

    $this->logger = $logger;
  }

  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
   
    if($this->auth->guest())
    {
      $this->logger->log('Guest User requested '  . $request->method() . ' request on ' . $request->fullUrl() );
    }
    else
    {
      $this->logger->log($this->auth->user()->full_name . '( ID - '. $this->auth->user()->id .' ) requested ' .  $request->method() . ' request on ' .  $request->fullUrl());
    }

    return $next($request);
  }
}
