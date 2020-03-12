<?php

namespace FireApps\Constants;

class ShopifyURL
{
    /**
     * Token URL, no version include
     */
    const TOKEN = "https://%s/admin/oauth/access_token";

    /**
     * 
     */
    const SHOP = "https://%s/admin/api/2020-01/shop.json";

    const PRODUCTS = "https://%s/admin/api/2020-01/products.json";
}