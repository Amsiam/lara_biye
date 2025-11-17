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
        // Send profile completion reminders every 45 days at 9:00 AM
        // Runs daily at 9 AM, but only executes if 45 days have passed since last run
        $schedule->command('profile:send-completion-reminders')
            ->dailyAt('09:00')
            ->when(function () {
                $lastRun = cache('profile_reminder_last_run');
                if (!$lastRun) {
                    cache(['profile_reminder_last_run' => now()], now()->addDays(45));
                    return true;
                }

                if (now()->diffInDays($lastRun) >= 45) {
                    cache(['profile_reminder_last_run' => now()], now()->addDays(45));
                    return true;
                }

                return false;
            })
            ->appendOutputTo(storage_path('logs/cron-reminders.log'));
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
