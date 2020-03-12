<?php

namespace FireApps\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use FireApps\Models\Product;

class ProductRepository extends BaseRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return Product::class;
    }

    public function createMulti($arr)
    {
        return Product::insert($arr);
    }
}
