<?php

namespace App\Http\Middleware\Custom;

use Closure;
use Illuminate\Auth\AuthenticationException;

class Admin
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    // Grab this before the $request data is cleared
    $input = $request->user()->only(['email']);

    if(! $request->user()->isAdmin()) {
      auth()->check()
        ? auth()->logout()
        : abort(403, 'This action is not authorized!');
    }

    if( ! $request->user()) {
      return redirect()->back()
        ->withInput($input)
        ->withErrors(['email' => 'This email is unauthorized']);
    }

    return $next($request);
  }
}
