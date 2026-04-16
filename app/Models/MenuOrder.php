<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuOrder extends Model
{
    protected $fillable = [
        'store_id',
        'device_id',
        'order_id',
        'order_type',
        'customer_name',
        'customer_phone',
        'delivery_address',
        'instructions',
        'subtotal',
        'discount',
        'delivery_fee',
        'total',
        'status',
        'checked',
        'customer_id',
        'table_no',
    ];

    protected $casts = [
        'store_id' => 'integer',
        'subtotal' => 'float',
        'discount' => 'float',
        'delivery_fee' => 'float',
        'total' => 'float',
    ];

    public function items()
    {
        return $this->hasMany(MenuOrderItem::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details()
    {
        return $this->hasMany(MenuOrderItem::class, 'menu_order_id');
    }

    public function scopeStoreOrder($query)
    {
        return $query->where(function ($q) {
            $q->where('order_type', 'dine-in')->orWhere('order_type', 'delivery');
        });
    }

    public function scopeNotDigitalOrder($query)
    {
        return $query->where(function ($q){
            $q->whereNotIn('payment_method', ['digital_payment','offline_payment'])->orwhereNot('order_status' , 'pending');
        });
    }
}
