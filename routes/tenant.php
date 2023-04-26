<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\subDomainController;
use Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

// Route::group(['domain' => '{domain}'], function() { 
//   //  Route::view('/dashboard', 'dashboard')->name('dashboard');
//   Route::get('/dashboard', function () {
//   //  $subdomain = "hello";
//     return 'welcome to subDomain'.Auth::user()->id;
// });
// });
Route::domain('{tenant}.mvpbits.com')->middleware('tenant')->group(function () {
    Route::get('/', function () {
       
        $currenturl = url()->full();
        $subdomain =  $currenturl;
        return view('subdomainHome.home', ['subdomain' => $subdomain]);
      
        Route::get('/', function () {
            $subdomain = "hello";
            return view('subdomainHome.home', ['subdomain' => $subdomain]);
        });
    });
});
Route::middleware([
    'web',
    'auth',
    // 'domain',
    InitializeTenancyBySubdomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    // Route::group(['domain' => '{domain}'], function() { 
    //       //  Route::view('/dashboard', 'dashboard')->name('dashboard');
    //       Route::get('/dashboard', function () {
    //       //  $subdomain = "hello";
    //         return view('dashboard');
    //     });
    //     });
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/dashboard1', [ProfileController::class, 'dashboard'])->name('dashboard1');
    Route::get('/as', [ProfileController::class, 'subDomainHome']);
   // Route::view('/1', 'welcome')->name('home1');

    Route::resource('tasks', TaskController::class);
    Route::resource('projects', ProjectController::class);
    Route::resource('subDomain', subDomainController::class);
    //changePrimeryStatus   changeRedirectStatus
    Route::get('/changePrimeryStatus', [subDomainController::class, 'changePrimeryStatus'])->name('changePrimeryStatus');
    Route::get('/changeRedirectStatus', [subDomainController::class, 'changeRedirectStatus'])->name('changeRedirectStatus');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
})->middleware('redirectSubdomain');
