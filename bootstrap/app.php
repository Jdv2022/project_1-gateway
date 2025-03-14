<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\PrePostLogs;
use App\Http\Middleware\EncryptDecrypt;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(PrePostLogs::class);
        $middleware->append(EncryptDecrypt::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (\Exception $e, $request) {
            $responseData = [
                'status' => 'Error',
                'error_type' => get_class($e),
                'message' => $e->getMessage(),
                'payload' => null,
            ];

            if(env('APP_DEBUG')) {
                $responseData['file'] = $e->getFile();
                $responseData['line'] = $e->getLine();
                $responseData['payload'] = $request->all();
            }

            return response()->json($responseData, 500);
        });
    })
    ->create();
