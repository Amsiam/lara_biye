<?php

namespace App\Models;

use App\Traits\UpdatesProfileCompletion;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use UpdatesProfileCompletion;

    protected $guarded = ["id"];
}
