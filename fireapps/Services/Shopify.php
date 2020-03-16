<?php

namespace FireApps\Services;

use FireApps\Contracts\ShopifyContract;
use FireApps\Constants\ShopifyURL;
use GuzzleHttp\Client;
use \Exception;

class Shopify implements ShopifyContract
{

    function post($uri, $token, $body_arr)
    {
        try {
            $client = new Client();

            $response = $client->post($uri, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-Shopify-Access-Token' => $token
                ],
                'body' => json_encode($body_arr)
            ]);

            return json_decode((string)$response->getBody());
        } catch (Exception $ex) {
            throw $ex;
        }

        return false;
    }

    function get($uri, $token)
    {
        try {
            $client = new Client();
            $response = $client->get($uri, [
                'headers' => [
                    'X-Shopify-Access-Token' => $token
                ]
            ]);

            return json_decode((string)$response->getBody());
        } catch (Exception $ex) {
            throw $ex;
        }

        return false;
    }

    /**
     * Get redirect URl when authorized 
     * 
     * @author duynk <duynk@fireapps.vn>
     * @since 11-03-2020
     * 
     * @return string Redirect URL
     */
    public function redirectUrl()
    {
        return route('shopify.redirect_url');
    }

    /**
     * Generate Shopify Auth Url
     *
     * @param string $shopUrl
     *
     * @return string Shopify Authorized URL
     */
    public function shopifyAuthUrl($shopUrl)
    {
        $client_id = config('shopify.api_key');
        $scopes = config('shopify.scope');
        $redirect_uri = $this->redirectUrl();

        return sprintf("https://%s.myshopify.com/admin/oauth/authorize?client_id=%s&scope=%s&redirect_uri=%s", $shopUrl, $client_id, $scopes, $redirect_uri);
    }

    /**
     * Convert data from Shopify
     *
     * @param string|array $data
     *
     * @return array|boolean
     */
    private function convertRequestData($data)
    {
        $tmp = [];
        if (is_string($data)) {
            $each = explode('&', $data);
            foreach ($each as $e) {
                list($key, $val) = explode('=', $e);
                $tmp[$key] = $val;
            }
        } elseif (is_array($data)) {
            $tmp = $data;
        } else {
            return false;
        }

        return $tmp;
    }

    /**
     * Verify request return from Shopify
     *
     * @param array $data
     *
     * @return boolean
     */
    public function verifyRequest($data)
    {
        $data = $this->convertRequestData($data);
        if ($data == false)
            return false;

        // Timestamp check; 1 hour tolerance
        if (($data['timestamp'] - time() > 3600))
            return false;

        if (!array_key_exists('hmac', $data))
            return false;

        // HMAC Validation
        $queryString = http_build_query([
            'code'      => $data['code'],
            'shop'      => $data['shop'],
            'timestamp' => $data['timestamp']
        ]);
        $calculated  = hash_hmac('sha256', $queryString, config('shopify.api_secret_key'));
        $match       = $data['hmac'];

        return $calculated === $match;
    }

    /**
     * Get Shop name from shop URL
     *
     * @param string $shopUrl
     *
     * @return string Shop name only
     */
    public function getShopNameFromURL($shopUrl)
    {
        return str_replace('.myshopify.com', '', $shopUrl);
    }

    /**
     * Get Token from Shopify
     *
     * @param string $shopUrl
     * @param string $code Authorized code from Shopify
     *
     * @return object|false
     */
    public function getAccessToken($shopUrl, $code)
    {
        try {
            $url = sprintf(ShopifyURL::TOKEN, $shopUrl);

            $client = new Client();
            $response = $client->post($url, [
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'body' => json_encode([
                    'client_id' => config('shopify.api_key'),
                    'client_secret' => config('shopify.api_secret_key'),
                    'code' => $code
                ])
            ]);

            $obj = json_decode($response->getBody());
            return $obj;
        } catch (Exception $ex) {
            \Debugbar::info($ex->getMessage());
        }

        return false;
    }

    /**
     * Get Shop URL generate token
     *
     * @param string $shopUrl
     *
     * @return string Shop Token URL
     */
    public function generateUrl($url_format, ...$agrs)
    {
        return vsprintf($url_format, $agrs);
    }

    public function getShopInfo($shopUrl, $token)
    {
        $url = $this->generateUrl(ShopifyURL::SHOP, $shopUrl);
        $shop = $this->get($url, $token);

        return $shop->shop;
    }

    public function getProducts($shopUrl, $token)
    {
        $url = $this->generateUrl(ShopifyURL::PRODUCTS, $shopUrl);
        $response = $this->get($url, $token);

        return $response->products;
    }

    public function addMetaMessage($product, $message)
    {
        $shop = $product->shop;
        $url = $this->generateUrl(ShopifyURL::PRODUCT_META, $shop->domain_url, $product->id);
        $data = new \stdClass;
        $data->metafield = (object) [
            'namespace' => 'fireapps',
            'key' => 'message',
            'value' => $message,
            'value_type' => 'string'
        ];
       
        $response = $this->post($url, $shop->token, $data);
        return $response;
    }

    public function getMetaMessage($product)
    {
        $shop = $product->shop;

        $url = $this->generateUrl(ShopifyURL::PRODUCT_META, $shop->domain_url, $product->id) . '?namespace=fireapps&key=message';
        $response = $this->get($url, $shop->token);
        if (!(property_exists($response, 'metafields') && count($response->metafields) > 0)) {
            return '';
        }

        $meta = $response->metafields[0];
        return $meta->value;
    }
}
