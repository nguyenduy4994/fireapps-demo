<?php

namespace FireApps\Api;

use Illuminate\Http\Request;
use FireApps\Repositories\ProductRepository;
use FireApps\Jobs\ProcessShopProducts;
use Shopify;

class ShopApi extends BaseApi
{
    /**
     * Product Repository
     *
     * @var FireApps\Repositories\ProductRepository
     */
    private $productRepository;

    /**
     * Shop Api constructor
     *
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Get All products
     * If shop not synced products then add a Job 
     * to sync all products from Shopify
     *
     * @param Request $request
     *
     * @return Json Shop's products
     */
    public function products(Request $request)
    {
        $shop = $request->user()->shop;

        $data = [];
        if ($shop->products_synced) {
            $data = $this->productRepository->all();
        } else {
            $data = $this->syncProducts($shop);
        }

        return response()->json($data);
    }

    /**
     * Sync all products of $shop
     *
     * @param FireApps\Models\Shop $shop
     *
     * @return array Products data
     */
    private function syncProducts($shop)
    {
        $products = Shopify::getProducts($shop->domain_url, $shop->token);

        $data = [];
        foreach ($products as $product) {
            $data[] = [
                'id' => $product->id,
                'shop_id' => $shop->id,
                'title' => $product->title,
                'vendor' => $product->vendor,
                'message' => ''
            ];
        }

        // Add to Queue to Process shop's products
        dispatch(new ProcessShopProducts($data));

        $shop->products_synced = true;
        $shop->save();

        return $data;
    }

    /**
     * Add message to products
     *
     * @param Request $request
     * @param bigint $product_id
     *
     * @return json response
     */
    public function addMessage(Request $request, $product_id)
    {
        $product = $this->productRepository->find($product_id);
        $product->message = $request->message;
        $product->save();

        Shopify::addMetaMessage($product, $request->message);
        return response()->json(true);
    }
}
