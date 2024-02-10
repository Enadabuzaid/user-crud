<?php

namespace App\Providers;

use App\Services\UserService;
use Illuminate\Support\ServiceProvider;
use Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(UserService::class, function ($app) {
            return new UserService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::macro('softDeletes', function ($prefix, $controller) {
            // Trashed items
            Route::get($prefix.'/trashed', [$controller, 'trashed'])->name($prefix.'.trashed');

            // Restore item
            Route::patch($prefix.'/{user}/restore', [$controller, 'restore'])->name($prefix.'.restore');

            // Permanently delete item
            Route::delete($prefix.'/{user}/delete', [$controller, 'delete'])->name($prefix.'.delete');
        });

    }
}
