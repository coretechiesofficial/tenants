<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Hyn\Tenancy\Facades\Tenancy;
use DB;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // $hostname = Tenancy::hostname();
       
        

        return $next($request);
    }
}
