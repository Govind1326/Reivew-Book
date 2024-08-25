<?php

namespace App\Providers;

use App\Http\View\Composers\SidebarComposer;
use Illuminate\Support\Facades\View; 
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Paginator::useBootstrapFive();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.sidebar', SidebarComposer::class);
    }
}
