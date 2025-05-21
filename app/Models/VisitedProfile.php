<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitedProfile extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function profile()
    {
        return $this->belongsTo(User::class, 'visited_user_id');
    }
}
