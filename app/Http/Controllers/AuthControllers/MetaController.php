<?php

namespace App\Http\Controllers\AuthControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\ApiBaseController;

class MetaController extends ApiBaseController {
    
    public function __construct() {
        
    }

    public function metaData():array {
        $metaData = [
            'gateway_version' => '1.0',
            'backend_version' => '1.0',
            'frontend_version' => '1.0',
            'cue' => $this->encryptWithPublicKey()
        ];
        return $this->returnSuccess([], "Startup Data", $metaData);
    }

    public function cue() {
        $privateKeyPath = storage_path('keys/private_key.pem');
        $privateKey = file_get_contents($privateKeyPath);
        $metaData = [
            'gateway_version' => '1.0',
            'backend_version' => '1.0',
            'frontend_version' => '1.0',
            'cue' => $privateKey
        ];
        return $this->returnSuccess([], "Follow up Data", $metaData);
    }

    private function encryptWithPublicKey() {
        $publicKeyPath = storage_path('keys/public_key.pem');
        $publicKey = file_get_contents($publicKeyPath);
    
        openssl_public_encrypt(config('app.key'), $encryptedData, $publicKey);
        
        return base64_encode($encryptedData); 
    }
    
}
