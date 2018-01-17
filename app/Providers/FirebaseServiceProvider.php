<?php

namespace App\Providers;

use App\Firebase\FirebaseConnection;
use Illuminate\Support\ServiceProvider;

class FirebaseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton('App\Firebase\FirebaseConnection', function ($app) {
            return new FirebaseConnection();
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
