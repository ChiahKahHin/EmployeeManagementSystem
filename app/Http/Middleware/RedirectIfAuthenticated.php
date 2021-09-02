<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if(Auth::user()->isAdmin() || Auth::user()->isHrManager()){
                    return redirect()->route("dashboard1");
                }
                elseif(Auth::user()->isManager() || Auth::user()->isEmployee()){
                    return redirect()->route("dashboard2");
                }
                else{
                    return redirect()->route("login");
                }
            }
        }

        return $next($request);
    }
}
