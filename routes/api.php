<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return \Auth::user()->shop;
});

Route::name('shopify.')->prefix('shopify')->namespace('\FireApps\Api')->group(function() {
    Route::get('authenticate', 'AuthenticateApi@getAuthUrl')->name('authenticate');

    Route::middleware('auth:api')->group(function() {
        Route::get('/products', 'ShopApi@products')->name('shop.products');

        Route::post('/products/{product_id}/message', 'ShopApi@addMessage')->name('shop.message');
    });
});
