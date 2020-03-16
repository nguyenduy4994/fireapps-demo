<?php

namespace FireApps\Constants;

class ShopifyURL
{
    /**
     * Token URL, no version include
     */
    const TOKEN = "https://%s/admin/oauth/access_token";

    /**
     * Shop Api endpoint
     * 
     * https://shopify.dev/docs/admin-api/rest/reference/store-properties/shop#show-2020-01
     */
    const SHOP = "https://%s/admin/api/2020-01/shop.json";

    /**
     * Products API endpoint
     * 
     * https://shopify.dev/docs/admin-api/rest/reference/products/product#index-2020-01
     */
    const PRODUCTS = "https://%s/admin/api/2020-01/products.json";

    /**
     * Product metafields API endpoint
     * 
     * https://shopify.dev/docs/admin-api/rest/reference/metafield#index-2020-01
     */
    const PRODUCT_META = "https://%s/admin/api/2020-01/products/%s/metafields.json";
}