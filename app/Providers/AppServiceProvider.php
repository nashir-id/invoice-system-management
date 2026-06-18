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
    public function boot(): void
    {
        Blade::if('owner', fn () => auth()->check() && auth()->user()->role === 'owner');
        Blade::if('adminup', fn () => auth()->check() && in_array(auth()->user()->role, ['owner', 'admin'], true));
        Blade::if('staff', fn () => auth()->check() && auth()->user()->role === 'staff');
    }
}
