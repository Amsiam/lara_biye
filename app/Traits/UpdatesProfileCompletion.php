<?php

namespace App\Traits;

trait UpdatesProfileCompletion
{
    protected static function bootUpdatesProfileCompletion(): void
    {
        static::saved(function ($model) {
            if ($model->user) {
                $model->user->updateProfileCompletion();
            }
        });
    }
}
