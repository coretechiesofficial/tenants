<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain as BaseInitializeTenancyBySubdomain;
use DB;
use Auth;
use Closure;

class InitializeTenancyBySubdomain extends BaseInitializeTenancyBySubdomain
{
   
    public function handle($request, Closure $next)
    {
        // $response = $this->identificationMiddleware->initializeTenancy($request, $next);

        // This line will cause the error if $response is null
        $response=headers->set('X-Tenant-Id', '1');
    
        return $response;
    }
    
    public function initializeTenancy($request, $next, ...$resolverArguments)
    {
        //dd(Auth::user());
        
        $currenturl = url()->full();
        $cuURL=   explode("https://",$currenturl);
        $mainCurrentUrl = explode("/dashboard",$cuURL[1]);
        $mainSubDomaiCurrentUrl = explode('.'.config('tenancy.central_domains')[0],$mainCurrentUrl[0]);
         // print_r($mainSubDomaiCurrentUrl);
         // exit;
         $userTenant = DB::table('multi_tenant.tenant_user')->where('user_id',Auth::user()->id)->get();
         $subDomainPrimery= '';
         foreach ($userTenant as $key => $tenant) {
             $subDomain=DB::table('multi_tenant.domains')->where('tenant_id',$tenant->tenant_id)->first();
 
             
             if ( $subDomain->status ==1) {
                 $subDomainPrimery = $subDomain->domain;
             } 
             
             
         }
         $subDomainmain=DB::table('multi_tenant.domains')->where('domain',$mainSubDomaiCurrentUrl[0])->first();
         if ($mainSubDomaiCurrentUrl[0] == $subDomainmain->domain ) {
                if ($subDomainmain->status ==1) {
                    
                    $url= 'https://' . $subDomainPrimery . '.'. config('tenancy.central_domains')[0] .'/dashboard';
                    return redirect($url);
                }
                if ($subDomainmain->is_redirect_url == 1) {
                   
                    $url= 'https://' . $subDomainPrimery . '.'. config('tenancy.central_domains')[0] .'/dashboard';
                    return redirect($url);
                }else {
                   
                }
            }
    }
}
