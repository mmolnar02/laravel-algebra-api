<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        apiPrefix: 'api/v1',
        //health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(
            function (Throwable $e) {
                return match (get_class($e)) {
                    NotFoundHttpException::class => response()->json(['message' => 'Not Found'], 404),
                    MethodNotAllowedHttpException::class => response()->json(['message' => 'Method Not Allowed'], 405),
                    AuthenticationException::class => response()->json(['message' => 'Unauthorized'], 401),
                    default => response()->json(['message' => 'Bad Request'], 400),
                };
            }
        );
    })->create();
