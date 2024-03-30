<?php

namespace App\Providers;

use App\Components\Managers\SessionManager;
use App\Components\Managers\UserManager;
use Illuminate\Support\ServiceProvider;

class SessionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->singleton('app.session', SessionManager::class);
        $this->app->singleton('app.user', UserManager::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
    }
}
