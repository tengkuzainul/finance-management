<?php

use App\Http\Middleware\CheckIsAdmin;
use App\Http\Middleware\CheckIsKaryawan;
use App\Http\Middleware\CheckUserActive;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Add middleware aliases
        $middleware->alias([
            'active' => CheckUserActive::class,
            'admin' => CheckIsAdmin::class,
            'karyawan' => CheckIsKaryawan::class,
        ]);

        // Append CheckUserActive to auth middleware group
        $middleware->appendToGroup('auth', [
            CheckUserActive::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
