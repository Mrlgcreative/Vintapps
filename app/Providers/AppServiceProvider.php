<?php

namespace App\Providers;

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
        if (! file_exists(public_path('storage')) && ! app()->runningInConsole()) {
            try {
                $this->app->make('files')->link(
                    storage_path('app/public'),
                    public_path('storage')
                );
            } catch (\Throwable $e) {
                logger()->error('Échec création lien symbolique storage: ' . $e->getMessage());
            }
        }
    }
}
