<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerJob extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
