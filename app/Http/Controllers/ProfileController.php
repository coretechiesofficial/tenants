<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use DB;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function dashboard()
    { 

      
        $currenturl = url()->full();

        
       $cuURL=   explode("https://",$currenturl);
       $mainCurrentUrl = explode("/dashboard",$cuURL[1]);
       $mainSubDomaiCurrentUrl = explode('.'.config('tenancy.central_domains')[0],$mainCurrentUrl[0]);
      
        $userTenant = DB::table('multi_tenant.tenant_user')->where('user_id',AUth::user()->id)->get();
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
              
                return redirect('https://' . $subDomainPrimery . '.'. config('tenancy.central_domains')[0] .'/dashboard');
           
            }
            if ($subDomainmain->is_redirect_url == 1) {
           
                return redirect('https://' . $subDomainPrimery . '.'. config('tenancy.central_domains')[0] .'/dashboard');
              
            }else {
                return view('404');
            }
        }
        return view('dashboard');
    }


    public function subDomainHome()
    {
       
         $currenturl = url()->full();

        
       $cuURL=   explode("https://",$currenturl);
       //    $mainCurrentUrl = explode("/dashboard",$cuURL[1]);
         $mainSubDomaiCurrentUrl = explode('.'.config('tenancy.central_domains')[0],$cuURL[1]);
         $subDomainmain=DB::table('multi_tenant.domains')->where('domain',$mainSubDomaiCurrentUrl[0])->first();
         $userTenant = DB::table('multi_tenant.tenant_user')->where('tenant_id', $subDomainmain->tenant_id)->get();
         $subDomainPrimery= '';
        foreach ($userTenant as $key => $tenant) {
            $subDomain=DB::table('multi_tenant.domains')->where('tenant_id',$tenant->tenant_id)->first();

            
            if ( $subDomain->status ==1) {
                $subDomainPrimery = $subDomain->domain;
            } 
            
            
        }
       
        if ($subDomainmain->status ==1) {
               
                return redirect('https://' . $subDomainPrimery . '.'. config('tenancy.central_domains')[0] .'/1');
             
            }
            if ($subDomainmain->is_redirect_url == 1) {
           
                return redirect('https://' . $subDomainPrimery . '.'. config('tenancy.central_domains')[0] .'/1');
             
            }else {
                return view('404');
            }

        return view('welcome');
    }
}
