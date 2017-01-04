<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class VerifyUser
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
    session()->put('url.intended', $request->url());

    if ($this->auth->guest()) {
      if($request->ajax()) {

        session()->forget('url.intended');

        return  response()->json([
            'success' => false,
            'errors' => [
                [
                    'message' => 'You have to login first'
                ]
            ],
            'data' => null
        ], 200);
      } else {
        return redirect()->route('users.login.get');
      }
    }

    if($this->auth->user()->mobile_verify != 1)
    {
      if($request->ajax()) {

        session()->forget('url.intended');

        return $next($request);
      }

      return redirect()->route('users.verify.get');
    }

    return $next($request);
  }
}
