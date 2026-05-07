<?php

namespace App\Providers;

use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // ->local() converts any UTC Carbon timestamp to the user's detected timezone
        Carbon::macro('local', function () {
            /** @var Carbon $this */
            return $this->copy()->setTimezone(config('app.timezone', 'Asia/Dhaka'));
        });
    }
}
