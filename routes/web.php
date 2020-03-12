<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/shopify/callback', 'Shopify\AuthenticateController@index')->name('shopify.redirect_url');

Route::get('{any}', function () {
    return view('app');
})->where('any', '.*');
