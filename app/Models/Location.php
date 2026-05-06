<?php

namespace App\Models;

use App\Traits\UpdatesProfileCompletion;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use UpdatesProfileCompletion;

    protected $guarded = ["id"];
}
