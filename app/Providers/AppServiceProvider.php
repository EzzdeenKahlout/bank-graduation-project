<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // @role directive
        Blade::if('role', function ($role) {
            return auth()->check() && auth()->user()->hasRole($role);
        });

        // @permission directive
        Blade::if('permission', function ($permission) {
            return auth()->check() && auth()->user()->hasPermission($permission);
        });

        // @hasanyrole directive
        Blade::if('hasanyrole', function ($roles) {
            return auth()->check() && auth()->user()->hasRole($roles);
        });

        // @hasallroles directive
        Blade::if('hasallroles', function ($roles) {
            return auth()->check() && auth()->user()->hasAllRoles($roles);
        });
    }
}
