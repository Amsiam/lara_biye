<?php

namespace App\Models;

use App\Traits\UpdatesProfileCompletion;
use Illuminate\Database\Eloquent\Model;

class HobbiesAndInterest extends Model
{
    use UpdatesProfileCompletion;

    protected $guarded = ["id"];
}
