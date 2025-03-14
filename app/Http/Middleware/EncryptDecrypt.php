<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Exception;
use Log;

class EncryptDecrypt {
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
        $request['payload'] = $this->decryptData($request);
        $response = $next($request);
        $data['payload'] = $this->encryptData($response);
        $response->setData($data);
        return $response;
    }

    private function encryptData(Response $response): string|object {
        $normalData = $response->getData(true);

        if(!isset($normalData['payload'])) {
            return "No Payload";
        }
        
        $normalData = $normalData['payload'];

        $key = base64_decode(env('APP_KEY'));
        $iv = random_bytes(16); 
        $ciphertext = openssl_encrypt($normalData, 'AES-256-CBC', $key, 0, $iv);
        $hmac = hash_hmac('sha256', $ciphertext, $key, true); 
    
        return base64_encode($iv . $ciphertext . $hmac); 
    }

    private function decryptData(Request $request): string|object {
        $encryptedData = $request->all();

        if (!isset($encryptedData['payload'])) {
            throw new Exception("EncryptDecrypt 'payload' property does not exist.");
        }

        $encryptedData = $encryptedData['payload'];
        $key = base64_decode(env('APP_KEY'));
        $decoded = base64_decode($encryptedData);
    
        if (strlen($decoded) < 48) { 
            throw new Exception("Invalid encrypted data.");
        }
    
        $iv = substr($decoded, 0, 16); 
        $hmac = substr($decoded, -32); 
        $ciphertext = substr($decoded, 16, -32); 
    
        $calculatedHmac = hash_hmac('sha256', $ciphertext, $key, true);
        // if(!hash_equals($calculatedHmac, $hmac)) {
        //     throw new Exception("HMAC verification failed. Possible tampering.");
        // }
    
        return openssl_decrypt($ciphertext, 'AES-256-CBC', $key, 0, $iv);
    }
    
}
