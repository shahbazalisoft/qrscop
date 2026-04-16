<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\CentralLogics\Helpers;

class MenuTemplate extends Model
{
    protected $fillable = ['title', 'template', 'priority', 'status'];

    protected $casts = [
        'status' => 'integer',
        'priority' => 'integer',
    ];

    public function subscriptionPackages()
    {
        return $this->belongsToMany(SubscriptionPackage::class, 'subscription_package_menu_template')->using(SubscriptionPackageMenuTemplate::class);
    }

    /**
     * Check if this template is active for current store
     */
    public function getStoreMenuAttribute()
    {
        $storeMenuId = Helpers::get_store_data()->menu_template ?? null;
        return $this->id == $storeMenuId ? 1 : 0;
    }
}
