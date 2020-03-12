<?php

namespace FireApps\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use FireApps\Models\Shop;

class ShopRepository extends BaseRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return Shop::class;
    }

    function updateOrCreateShop($name, $meta)
    {
        $shop = Shop::updateOrCreate(['name' => $name], $meta);
        return $shop;
    }
}
