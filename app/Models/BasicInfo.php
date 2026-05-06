<?php

namespace App\Models;

use App\Traits\UpdatesProfileCompletion;
use Illuminate\Database\Eloquent\Model;

class BasicInfo extends Model
{
    use UpdatesProfileCompletion;

    protected $guarded = ["id"];

    protected $attributes = [
        'image' => 'default.png',
    ];
}
