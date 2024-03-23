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
        if (!$this->app->runningInConsole()) {
            $this->app->singleton('app.session', SessionManager::class);
        }

        $this->app->singleton('app.user', UserManager::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
    }
}
