<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SubscriptionPackageMenuTemplate extends Pivot
{
    use HasFactory;

    protected $casts = [
        'id'=>'integer',
        'subscription_package_id'=>'integer',
        'menu_template_id'=>'integer'
    ];
}
