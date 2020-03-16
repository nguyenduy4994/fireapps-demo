<?php

namespace App\Http\Controllers\Shopify;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Shopify;
use FireApps\Repositories\ShopRepository;
use FireApps\Repositories\UserRepository;
use Auth;

class AuthenticateController extends Controller
{
    public function index(Request $request, ShopRepository $shopRepository, UserRepository $userRepository)
    {
        if (!Shopify::verifyRequest($request->all())) {
            return redirect('/')->with('errors', 'Failed');
        }

        $shop_token = Shopify::getAccessToken($request->shop, $request->code);
        $shop_info = Shopify::getShopInfo($request->shop, $shop_token->access_token);

        $shop_name = Shopify::getShopNameFromURL($shop_info->myshopify_domain);
        $shop_meta = [
            'shop_id' => $shop_info->id,
            'name' => $shop_name,
            'domain_url' => $shop_info->domain,
            'myshopify_domain' => $shop_info->myshopify_domain,
            'store_name' => $shop_info->name,
            'token' => $shop_token->access_token,
            'token_valid' => true
        ];
        $shop = $shopRepository->updateOrCreate(['name' => $shop_name], $shop_meta);
        $user = $shop->user;
        if (!$user) {
            $user = $userRepository->create([
                'name' => $shop_info->shop_owner,
                'email' => $shop_info->email,
                'password' => bcrypt(\Str::random(10))
            ]);
            
            $shop->user()->associate($user);
            $shop->save();
        }

        $user_token = $this->guard()->login($user);

        return redirect('/auth?token=' . $user_token);
    }

    protected function guard()
    {
        return Auth::guard();
    }
}
