<?php

namespace FireApps\Api;

use Illuminate\Http\Request;
use Shopify;

class AuthenticateApi extends BaseApi
{
    /**
     * Get Authorized URL for Shop
     *
     * @author DuyNK <duynk@fireapps.vn>
     * @since 1.0.0
     * @param Request $request
     *
     * @return string
     */
    public function getAuthUrl(Request $request) 
    {
        $res = [
            'redirect_url' => Shopify::shopifyAuthUrl($request->shop_url)
        ];

        return response()->json($res);
    }

}