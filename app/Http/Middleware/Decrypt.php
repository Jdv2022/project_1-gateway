<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Exception;
use Log;
use App\Http\Contracts\RequestModel;

class Decrypt {
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
		Log::debug("Decrypt Middleware");

		$endPointToIgnore = [
			'api/meta/data',
			'api/web/private/validate/token',
		];

		if(in_array($request->path(), $endPointToIgnore)) {
			return $next($request);
		}

		$encryptedData = $request->all();

		if(!array_key_exists('payload', $encryptedData)) {
			throw new Exception("Decrypt 'payload' property does not exist.");
		}

		$request->merge([
			'payload' => $this->decryptData($encryptedData['payload'])
		]);

        return $next($request);
    }

    function decryptData(string $encryptedData):array {
        $appKey = config('app.key');
        if(!$appKey || strpos($appKey, 'base64:') !== 0) {
            throw new Exception("Invalid APP_KEY format.");
        }
		
        $key = base64_decode(substr($appKey, 7)); 
        $decoded = base64_decode($encryptedData);
        
        if(strlen($decoded) < 48) { 
            throw new Exception("Invalid encrypted data.");
        }
    
        $iv = substr($decoded, 0, 16);
        $ciphertext = substr($decoded, 16, -32);
        $hmac = substr($decoded, -32);
    
        // Validate HMAC
        $calculatedHmac = hash_hmac('sha256', $iv . $ciphertext, $key, true);
        if (!hash_equals($hmac, $calculatedHmac)) {
            throw new Exception("HMAC validation failed.");
        }
    
        $decrypted = openssl_decrypt($ciphertext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
    
        if ($decrypted === false) {
            throw new Exception("Decryption failed.");
        }
    
        return json_decode($decrypted, true) ?? [];
    }
    
}