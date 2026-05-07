<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetUserTimezone
{
    public function handle(Request $request, Closure $next)
    {
        $timezone = $_COOKIE['user_timezone'] ?? 'Asia/Dhaka';

        if (in_array($timezone, timezone_identifiers_list())) {
            config(['app.timezone' => $timezone]);
        }

        return $next($request);
    }
}
