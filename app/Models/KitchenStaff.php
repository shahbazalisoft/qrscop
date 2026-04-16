<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class KitchenStaff extends Authenticatable
{
    use Notifiable;

    protected $table = 'kitchen_staff';

    protected $fillable = [
        'f_name', 'l_name', 'email', 'phone', 'password',
        'store_id', 'vendor_id', 'status', 'is_logged_in',
        'login_remember_token', 'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'store_id' => 'integer',
        'vendor_id' => 'integer',
        'status' => 'boolean',
        'is_logged_in' => 'boolean',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
