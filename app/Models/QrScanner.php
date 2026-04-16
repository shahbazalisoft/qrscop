<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrScanner extends Model
{
    use HasFactory;

    protected $table = 'qr_scanners';

    protected $fillable = [
        'store_id',
        'qr_scanner',
        'table_no',
        'status',
    ];
}
