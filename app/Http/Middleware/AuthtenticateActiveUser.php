<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthtenticateActiveUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$guard=null)
    {
        if(Auth::guard($guard)->user()->active){
            return $next($request);
        }else{
            Auth::guard($guard)->logout();
            return response('User not active. Please contact system administrator.',401);

        }
    }
}
