<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    protected $guarded = ['id'];

    public static function send($title, $type = null, $description = null, $link = null)
    {
        return self::create([
            'title' => $title,
            'type' => $type,
            'description' => $description,
            'link' => $link,
        ]);
    }
}
