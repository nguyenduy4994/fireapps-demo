<?php

namespace FireApps\Api;

use Illuminate\Http\Request;
use FireApps\Repositories\ProductRepository;
use Shopify;

class ShopApi extends BaseApi
{
    public function products(Request $request, ProductRepository $product_repository)
    {
        $shop = $request->user()->shop;

        if ($shop->products_synced == false) {
            $shopify_products = Shopify::getProducts($shop->domain_url, $shop->token);

            $products_data = [];
            foreach ($shopify_products as $product) {
                $products_data[] = [
                    'id' => $product->id,
                    'shop_id' => $shop->id,
                    'title' => $product->title,
                    'vendor' => $product->vendor
                ];
            }

            if (!$product_repository->createMulti($products_data)) {
                // false
            }

            $shop->products_synced = true;
            $shop->save();
        }

        return response()->json(Shopify::getProducts($shop->domain_url, $shop->token));
    }
}
