<?php

namespace FireApps\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'id',
        'shop_id',
        'title',
        'vendor'
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
