<?php

use App\Exceptions\Handler as ExceptionHandler;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middlewares\Handler as MiddlewareHandler;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(new MiddlewareHandler())
//    ->withMiddleware(function (Middleware $middleware) {
//        //
//    })
    ->withExceptions(fn() => new ExceptionHandler(app()))

    ->withCommands([__DIR__ . '/../app/Console/Commands'])
    ->create()
    ;


