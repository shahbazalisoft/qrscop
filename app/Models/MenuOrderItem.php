<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuOrderItem extends Model
{
    protected $fillable = [
        'menu_order_id',
        'item_id',
        'item_name',
        'item_price',
        'quantity',
        'size',
        'image',
    ];

    protected $casts = [
        'menu_order_id' => 'integer',
        'item_price' => 'float',
        'quantity' => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(MenuOrder::class, 'menu_order_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
