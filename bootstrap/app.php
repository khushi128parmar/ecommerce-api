<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;

// use Throwable;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

// RATE LIMITER
// RateLimiter::for('login', function (Request $request) {

//     return Limit::perMinute(5)->by(

//         $request->ip()
//     );
// });


return Application::configure(basePath: dirname(__DIR__))

    ->withRouting(
        web: __DIR__ . '/../routes/web.php',

        api: __DIR__ . '/../routes/api.php',

        commands: __DIR__ . '/../routes/console.php',

        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware): void {

        $middleware->alias([

            'role' => RoleMiddleware::class,

            'permission' => PermissionMiddleware::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (

            Throwable $e,

            $request
        ) {

            if ($request->is('api/*')) {

                return response()->json([

                    'success' => false,

                    'message' => $e->getMessage(),
                ], 500);
            }
        });
    })

    ->create();
