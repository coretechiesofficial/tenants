<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        \Route::pattern('domain', '[a-z0-9.\-]+'); 
    parent::boot();
        $this->configureRateLimiting();

        $this->mapApiRoutes();
        $this->mapWebRoutes();
        
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }

    protected function mapWebRoutes()
    {
        foreach ($this->centralDomains() as $domain) {
            Route::middleware('web')
                ->domain($domain)
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        }
    }

    protected function centralDomains(): array
    {
        return config('tenancy.central_domains');
    }

    protected function mapApiRoutes()
    {
        foreach ($this->centralDomains() as $domain) {
            Route::prefix('api')
                ->domain($domain)
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));
        }
    }
    public function map()
{
    $this->mapApiRoutes();
    $this->mapWebRoutes();
    $this->mapTenantRoutes();
}

protected function mapTenantRoutes()
{
    Route::middleware(['web', 'auth', 'domain'])
        ->namespace("$this->namespace\Tenant")
        ->name('tenant.')
        ->group(base_path('routes/tenant.php'));
}
protected function mapAuthRoutes()
{
    Route::middleware(['web', 'domain'])
        ->namespace("$this->namespace\Auth")
        ->name('auth.')
        ->group(base_path('routes/auth.php'));
}
}
