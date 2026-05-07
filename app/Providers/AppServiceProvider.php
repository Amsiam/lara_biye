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
            $tz = $_COOKIE['user_timezone'] ?? config('app.timezone', 'Asia/Dhaka');
            /** @var Carbon $this */
            return $this->copy()->setTimezone(
                in_array($tz, timezone_identifiers_list()) ? $tz : 'Asia/Dhaka'
            );
        });
    }
}
