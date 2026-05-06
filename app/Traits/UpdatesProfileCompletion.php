<?php

namespace App\Traits;

use App\Models\User;

trait UpdatesProfileCompletion
{
    protected static function bootUpdatesProfileCompletion(): void
    {
        static::saved(function ($model) {
            if ($model->user_id) {
                User::whereKey($model->user_id)
                    ->first()
                    ?->updateProfileCompletion();
            }
        });
    }
}
