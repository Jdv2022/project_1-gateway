<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\PreRequestLogs;
use App\Http\Middleware\Decrypt;
use App\Http\Middleware\PostRequestLogs;
use App\Http\Middleware\Encrypt;
use App\Http\Middleware\PreGeneralProcess;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(prepend: [
			Decrypt::class,
			PreRequestLogs::class,
			PreGeneralProcess::class,
        ]);
        $middleware->api(append: [
			Encrypt::class,
			PostRequestLogs::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (\Exception $e, $request) {
            if($e instanceof ValidationException) {
                return response()->json([
                    'status' => 'Error',
                    'error' => 1,
                    'message' => 'Validation failed',
                    'payload' => null,
                ], 422);
            }
			if($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
				return response()->json([
					'status' => 'Error',
					'error' => 1,
					'message' => 'Token has expired',
					'payload' => null,
				], 401);
			}
			if ($e instanceof \Tymon\JWTAuth\Exceptions\JWTException) {
				return response()->json([
					'status' => 'Error',
					'error' => 1,
					'message' => 'Token is missing or invalid',
					'payload' => null,
				], 401);
			}
			if($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
				return response()->json([
					'status' => 'Error',
					'error' => 1,
					'message' => 'Invalid token',
					'payload' => null,
				], 401);
			}
            $status = method_exists($e, 'getStatusCode') 
                ? $e->getStatusCode() 
                : 500;

            $responseData = [
                'status' => 'Error',
                'error' => 1,
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
