<?php

namespace Fireapps\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use FireApps\Services\Shopify;
use FireApps\Models\Shop;
use FireApps\Models\Observers\ShopObserver;

class FireAppsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        URL::forceScheme('https');

        $this->app->singleton(Shopify::class, function($app) {
            return new Shopify();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Observers register
        Shop::observe(ShopObserver::class);
    }
}
