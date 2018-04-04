<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminUserMiddleware
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
        if(Auth::user() and !Auth::user()->isAdmin()) {
            //flash()->error('Sorry, You have not permission to access.');
            abort(404);
            return redirect(url('my-account'));
        } else if(!Auth::user()) {
            return redirect(url('/login'));
        }
        return $next($request);
    }
}
