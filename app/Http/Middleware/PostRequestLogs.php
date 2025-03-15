<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Log;

class PostRequestLogs {
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
        $response = $next($request);
        $this->postRequestLogs($response, $request);
        return $response;
    }

    private function postRequestLogs(Response $response, Request $request): void {
        $status = $response->getStatusCode();
        Log::debug("Response data:\n" . json_encode(json_decode($response->getContent()), JSON_PRETTY_PRINT));        
        Log::info("Response Status: " . $status);
        Log::info("URL ORIGIN: " . $request->fullUrl());
        Log::info("<-----------------------------------[Post Controller Process]----------------------------------->");
    }

}
