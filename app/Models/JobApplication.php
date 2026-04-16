<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function careerJob()
    {
        return $this->belongsTo(CareerJob::class);
    }
}
