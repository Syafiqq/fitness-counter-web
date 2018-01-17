<?php

namespace App\Providers;

use App\Firebase\FirebaseUserProvider;
use App\Model\FirebaseUser;
use Illuminate\Auth\AuthManager;
use Illuminate\Auth\SessionGuard;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        /** @var AuthManager $auth */
        $auth = Auth::getFacadeRoot();

        $this->app->bind('App\Model\FirebaseUser', function ($app) {
            return new FirebaseUser($app->make('App\Firebase\FirebaseConnection'));
        });

        // add custom guard provider
        $auth->provider(
            'firebase', function ($app, array $config) {
            return new FirebaseUserProvider($config['model']);
        });

        // add custom guard
        $auth->extend('firebase', function ($app, $name, array $config) use ($auth) {
            return new SessionGuard($name, $auth->createUserProvider($config['provider']), $app['session.store']);
        });
    }
}
