<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );

        $exceptions->render(function (HttpException $e, Request $request) {
            // Biarkan JSON API menangani responnya sendiri
            if ($request->is('api/*')) {
                return null;
            }

            // Pakai view spesifik jika ada, selain itu fallback ke default
            $status = $e->getStatusCode();
            $view = 'errors.'.$status;
            if (! view()->exists($view)) {
                $view = 'errors.default';
            }

            return response()->view($view, ['code' => $status], $status);
        });
    })->create();
