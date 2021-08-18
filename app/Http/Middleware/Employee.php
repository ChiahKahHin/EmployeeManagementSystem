<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class Employee
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check())
            return redirect()->route("login");
        if (empty($roles)){
            return $next($request);
        }
        else{
            $role = Auth::user()->getShortRoleName();
            foreach ($roles as $key => $r) {
                if (Str::lower($role) == Str::lower($r))
                    return $next($request);
            }
        }

        return redirect()->route("login");
    }
}
