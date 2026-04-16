<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralTransaction extends Model
{
    use HasFactory;

    protected $table = 'referral_transactions';

    protected $fillable = [
        'store_id',
        'apply_store',
        'apply_referral_code',
        'days',
        'note',
    ];

    public function applyStore()
    {
        return $this->belongsTo(Store::class, 'apply_store');
    }
}
