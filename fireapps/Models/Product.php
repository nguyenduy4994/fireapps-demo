<?php

namespace FireApps\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'id',
        'shop_id',
        'title',
        'vendor',
        'message'
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
