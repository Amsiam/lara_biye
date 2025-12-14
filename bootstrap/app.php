<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withSchedule(function (Schedule $schedule) {
        // Send profile completion reminders to users based on registration date (Day 2, 3, 60, 120, 180)
        $schedule->command('profile:send-completion-reminders')
            ->dailyAt('09:00')
            ->appendOutputTo(storage_path('logs/cron-reminders.log'));

        // Cancel pending payments older than 2 days
        $schedule->command('payments:cancel-pending')
            ->dailyAt('00:00')
            ->appendOutputTo(storage_path('logs/cron-payments.log'));
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
