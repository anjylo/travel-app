<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Services\LocationService;

use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(LocationService::class, function ($app) {
            $location = $app->request->route()->parameter('location') ?? 'manila';

            return new LocationService($location);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
