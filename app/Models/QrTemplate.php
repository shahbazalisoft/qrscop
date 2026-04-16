<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrTemplate extends Model
{
    use HasFactory;

    protected $table = 'qr_templates';

    protected $fillable = [
        'name',
        'style',
        'status',
    ];
}
