<?php

namespace FireApps\Models\Observers;

use FireApps\Models\Shop;

class ShopObserver
{
    public function updating(Shop $shop) 
    {
        $old_token = $shop->getOriginal('token');

        if ($shop->token != $old_token) {
            $shop->products_synced = false;
            \Debugbar::info("new token");
        }
    }
}
