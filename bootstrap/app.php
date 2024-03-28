<?php

use App\Models\JWTModel;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // JWT are used on frontend, so we prevent encryption (no panic, there is a secure key !)
        $middleware->encryptCookies(
            except: [JWTModel::JWT_SESSION_COOKIE_NAME]
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
