<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Exception;
use Log;

class Encrypt {
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
        $response = $next($request);
        $originalData = $response->getData(true);
        $originalData['payload'] = $this->encryptData($response);
        $response->setData($originalData);
        return $response;
    }

    private function encryptData(Response $response): string|object {
        $normalData = $response->getData(true);

        if(!array_key_exists('payload', $normalData)) {
            throw new Exception("No Payload");
        }
        
        $normalData = json_encode($normalData['payload']);

        $key = base64_decode(env('APP_KEY'));
        $iv = random_bytes(16); 
        $ciphertext = openssl_encrypt($normalData, 'AES-256-CBC', $key, 0, $iv);
        $hmac = hash_hmac('sha256', $ciphertext, $key, true); 
    
        return base64_encode($iv . $ciphertext . $hmac); 
    }
    
}
