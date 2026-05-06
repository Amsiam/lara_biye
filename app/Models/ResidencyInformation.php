<?php

namespace App\Models;

use App\Traits\UpdatesProfileCompletion;
use Illuminate\Database\Eloquent\Model;

class ResidencyInformation extends Model
{
    use UpdatesProfileCompletion;

    protected $guarded = ["id"];
}
