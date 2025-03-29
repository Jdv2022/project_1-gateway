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
		$excludedRoutes = [
            'api/meta/data', 
        ];
		if($request->is($excludedRoutes)) {
			return $next($request);
		}

        $response = $next($request);
        $originalData = $response->getData(true);

        if(!array_key_exists('payload', $originalData)) {   
            throw new Exception("Encrypt 'payload' property does not exist.");
        }
        $isString = $originalData['payload'];
        if(!is_string($isString)) {
            $originalData['payload'] = json_encode($originalData['payload']);
        }
        $originalData['payload'] = $this->encryptData($originalData['payload']);
        $response->setData($originalData);
        return $response;
    }

	function encryptData($data): string {
		$appKey = config('app.key');
	
		if (!$appKey || strpos($appKey, 'base64:') !== 0) {
			throw new Exception("Invalid APP_KEY format.");
		}
	
		$key = base64_decode(substr($appKey, 7));
		$iv = random_bytes(16); 
	
		$ciphertext = openssl_encrypt($data, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
	
		$hmac = hash_hmac('sha256', $ciphertext, $key, true); 
	
		return base64_encode($iv . $ciphertext . $hmac);
	}
	
    
}
