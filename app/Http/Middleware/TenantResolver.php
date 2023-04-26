<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class TenantResolver
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $host = $request->getHost();
        // $tenant = DB::table('tenants')->where('domain', $host)->first();

        // if (true) {
        //     Config::set('database.connections.mysql.database', 'multi_tenant');
        //     // You can set other configuration settings here as needed
        // }

        return $next($request);
    }
}
