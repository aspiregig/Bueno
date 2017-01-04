<?php

namespace App\Http\Middleware;

use Flash;
use Closure;
use Illuminate\Contracts\Auth\Guard;

class CheckIfAreaIsSelected
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
  public function __construct(Guard $auth)
  {
    $this->auth = $auth;
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
    if (!session()->has('area_id'))
    {
      Flash::danger('Please select an area first');

      return redirect(route('pages.index'));
    }

    return $next($request);
  }
}
