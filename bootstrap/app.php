<?php

use App\Http\Middleware\Localization;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('api')
                ->prefix('api/mobile')
                ->group(base_path('routes/api/mobile-app.php'));

            Route::middleware('api')
                ->prefix('api/web')
                ->group(base_path('routes/api/web-app.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'localization' => Localization::class
        ]);

        $middleware->appendToGroup('api', [
            Localization::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
