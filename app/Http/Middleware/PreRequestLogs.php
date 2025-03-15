<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Log;

class PreRequestLogs {
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
        $this->preRequestLogs($request);
        return $next($request);
    }

    private function preRequestLogs(Request $request): void {
        Log::info("<-----------------------------------[Pre Controller Process]----------------------------------->");
        Log::info("Request URL: " . $request->fullUrl());
        Log::info("Request Method: " . $request->method());
        Log::debug("Request data: " . json_encode($request->all(), JSON_PRETTY_PRINT) );
    }

}
