<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        if ($exceptions instanceof NotFoundHttpException) {
            return response()->json(['message' => 'Resource not found.'], 404);
        }
    })->withSchedule(function (\Illuminate\Console\Scheduling\Schedule $schedule) {
        $schedule->call('app:fetch-news-articles')->hourly();
    })->create();
