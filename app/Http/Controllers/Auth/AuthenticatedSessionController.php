<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use DB;
use Illuminate\Support\Facades\Blade;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
       
        $request->authenticate();

        $request->session()->regenerate();
        // session()->put('user12', Auth::user());
        // $user = $request->cookie('user13');

        $userTenant = DB::table('multi_tenant.tenant_user')->where('user_id',Auth::user()->id)->get();
        $subDomainPrimery= '';
        foreach ($userTenant as $key => $tenant) {
            $subDomain=DB::table('multi_tenant.domains')->where('tenant_id',$tenant->tenant_id)->first();
            if ( $subDomain->status ==1) {
                $subDomainPrimery = $subDomain->domain;
            } 
            
            
        }
        // $useraaa = $request->session()->get('user12');
        // print_r(session()->get('user12'));
        // exit;


        if (Auth::user()->id ==13) {
           // Auth::login(Auth::user());
        //    $jsCode = Blade::compileString(view('my_script')->render());

        //    return response("<script>{$jsCode}</script>");
            return redirect('https://scanorderpay.in/dashboard');
        }

       
        return redirect('https://' . $subDomainPrimery . '.'. config('tenancy.central_domains')[0] .'/dashboard');
    
        // return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
