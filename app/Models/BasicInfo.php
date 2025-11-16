<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BasicInfo extends Model
{
    protected $guarded = ["id"];

    protected $attributes = [
        'image' => 'default.png',
    ];
}
