<?php

namespace FireApps\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $fillable = [
        'shop_id',
        'name',
        'domain_url',
        'myshopify_domain',
        'store_name',
        'token',
        'token_valid'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
