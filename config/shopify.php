<?php 

return [
    /**
     * Client ID
     */
    'api_key' => env('SHOPIFY_API_KEY'),

    /**
     * Client secret key
     */
    'api_secret_key' => env('SHOPIFY_API_SECRET_KEY'),
    
    /**
     * Scope for App when request Authorized to Shopify
     */
    'scope' => env('SHOPIFY_SCOPE'),
];
