<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Log;

class PreGeneralProcess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
		if(!isset($request->payload)) {
			return $next($request);
		}

        $decryptedPayload = is_array($request->payload) 
            ? $request->payload 
            : json_decode($request->payload, true);

        // Replace the entire request input with the decrypted payload
        $request->replace($decryptedPayload);
        return $next($request);
    }
}
