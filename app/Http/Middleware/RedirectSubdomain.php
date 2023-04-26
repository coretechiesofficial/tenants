<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
use DB;

class RedirectSubdomain
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

        
        // $host = $request->getHost();
        // $primarySubdomain = 'app.example.com';
        // $redirectUrl = '';
    
        // if ($host !== $primarySubdomain) {
        //     $redirectUrl = $request->getScheme() . '://' . $primarySubdomain . $request->getRequestUri();
        // }
    
        // if ($redirectUrl !== '') {
        //     return redirect($redirectUrl);
        // }
        $currenturl = url()->full();

        
       $cuURL=   explode("https://",$currenturl);
       $mainCurrentUrl = explode("/dashboard",$cuURL[1]);
       $mainSubDomaiCurrentUrl = explode('.'.config('tenancy.central_domains')[0],$mainCurrentUrl[0]);
        print_r(Auth::user());
        exit;
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
                    
                    // return redirect('https://' . $subDomainPrimery . '.'. config('tenancy.central_domains')[0] .'/dashboard');
                    $redirectUrl ='https://' . $subDomainPrimery . '.'. config('tenancy.central_domains')[0] .'/dashboard';
                    // return view('dashboard');
                }
                if ($subDomainmain->is_redirect_url == 1) {
                    $redirectUrl ='https://' . $subDomainPrimery . '.'. config('tenancy.central_domains')[0] .'/dashboard';
                   
                }else {
                    return view('404');
                }
                return redirect($redirectUrl);
            }
    
        return $next($request);
    }
}
