<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\PreRequestLogs;
use App\Http\Middleware\Decrypt;
use App\Http\Middleware\PostRequestLogs;
use App\Http\Middleware\Encrypt;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(prepend: [
            Decrypt::class,
            PreRequestLogs::class,
        ]);
        $middleware->api(append: [
            Encrypt::class,
            PostRequestLogs::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (\Exception $e, $request) {
            $status = method_exists($e, 'getStatusCode') 
                ? $e->getStatusCode() 
                : 500;

            $responseData = [
                'status' => 'Error',
                'error_type' => 1,
                'message' => $e->getMessage(),
                'payload' => null,
            ];

            if(env('APP_DEBUG')) {
                $responseData['file'] = $e->getFile();
                $responseData['line'] = $e->getLine();
                $responseData['payload'] = $request->all();
            }

            return response()->json($responseData, $status);
        });
    })
    ->create();
