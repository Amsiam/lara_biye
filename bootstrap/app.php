<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withSchedule(function (Schedule $schedule) {
        // Send profile completion reminders every 45 days at 9:00 AM
        $schedule->command('profile:send-completion-reminders')
                 ->days(45)
                 ->at('09:00');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
