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
                    'Content-Type' => 'application/json'
                ],
                'body' => json_encode($body_arr)
            ]);
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

            $obj = json_decode((string)$response->getBody());
            return $obj;
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
     * @param string $shop_url
     *
     * @return string Shopify Authorized URL
     */
    public function shopifyAuthUrl($shop_url)
    {
        $client_id = config('shopify.api_key');
        $scopes = config('shopify.scope');
        $redirect_uri = $this->redirectUrl();

        return sprintf("https://%s.myshopify.com/admin/oauth/authorize?client_id=%s&scope=%s&redirect_uri=%s", $shop_url, $client_id, $scopes, $redirect_uri);
    }

    /**
     * Convert data from Shopify
     *
     * @param string|array $request_data
     *
     * @return array|boolean
     */
    private function convertRequestData($request_data)
    {
        $tmp = [];
        if (is_string($request_data)) {
            $each = explode('&', $request_data);
            foreach ($each as $e) {
                list($key, $val) = explode('=', $e);
                $tmp[$key] = $val;
            }
        } elseif (is_array($request_data)) {
            $tmp = $request_data;
        } else {
            return false;
        }

        return $tmp;
    }

    /**
     * Verify request return from Shopify
     *
     * @param array $request_data
     *
     * @return boolean
     */
    public function verifyRequest($request_data)
    {
        $request_data = $this->convertRequestData($request_data);
        if ($request_data == false)
            return false;

        // Timestamp check; 1 hour tolerance
        if (($request_data['timestamp'] - time() > 3600))
            return false;

        if (!array_key_exists('hmac', $request_data))
            return false;

        // HMAC Validation
        $queryString = http_build_query([
            'code'      => $request_data['code'],
            'shop'      => $request_data['shop'],
            'timestamp' => $request_data['timestamp']
        ]);
        $calculated  = hash_hmac('sha256', $queryString, config('shopify.api_secret_key'));
        $match       = $request_data['hmac'];

        return $calculated === $match;
    }

    /**
     * Get Shop name from shop URL
     *
     * @param string $shop_url
     *
     * @return string Shop name only
     */
    public function getShopNameFromURL($shop_url)
    {
        return str_replace('.myshopify.com', '', $shop_url);
    }

    /**
     * Get Token from Shopify
     *
     * @param string $shop_url
     * @param string $code Authorized code from Shopify
     *
     * @return object|false
     */
    public function getAccessToken($shop_url, $code)
    {
        try {
            $url = sprintf(ShopifyURL::TOKEN, $shop_url);

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
     * @param string $shop_url
     *
     * @return string Shop Token URL
     */
    public function generateUrl($url_format, ...$agrs)
    {
        return vsprintf($url_format, $agrs);
    }

    public function getShopInfo($shop_url, $token)
    {
        $url = $this->generateUrl(ShopifyURL::SHOP, $shop_url);
        $shop = $this->get($url, $token);

        return $shop->shop;
    }

    public function getProducts($shop_url, $token)
    {
        $url = $this->generateUrl(ShopifyURL::PRODUCTS, $shop_url);
        $response = $this->get($url, $token);

        return $response->products;
    }
}
