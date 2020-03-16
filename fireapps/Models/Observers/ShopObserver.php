<?php

namespace FireApps\Models\Observers;

use FireApps\Models\Shop;

class ShopObserver
{
    /**
     * Event happen when updating model Shop
     *
     * @param Shop $shop
     *
     * @return void
     */
    public function updating(Shop $shop) 
    {
        $originalToken = $shop->getOriginal('token');

        if ($shop->token != $originalToken) {
            $shop->products_synced = false;
        }
    }
}
