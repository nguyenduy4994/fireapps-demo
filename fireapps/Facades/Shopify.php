<?php

namespace FireApps\Facades;

use Illuminate\Support\Facades\Facade;
use FireApps\Services\Shopify as ShopifyService;

class Shopify extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ShopifyService::class;
    }
}
