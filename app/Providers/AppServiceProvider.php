<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\App;
use Illuminate\Pagination\Paginator;

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
        Blade::if('eligible', function ($action) {
            $request = request();
            $middleware = App::make(\App\Http\Middleware\Eligible::class);
            return $middleware->handle($request, function () {}, $action) === null;
        });

        Blade::if('show', function ($action) {
            $request = request();
            $middleware = App::make(\App\Http\Middleware\Show::class);
            return $middleware->handle($request, function () {}, $action) === null;
        });

        Paginator::useBootstrapFive();
    }
}
