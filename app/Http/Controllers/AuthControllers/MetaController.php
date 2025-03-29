<?php

namespace App\Http\Controllers\AuthControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\ApiBaseController;
use Illuminate\Http\JsonResponse;

class MetaController extends ApiBaseController {
    
    public function __construct() {
        
    }

    public function metaData():JsonResponse {
        $metaData = [
            'gateway_version' => '1.0',
            'database_version' => '1.0',
            'frontend_version' => '1.0',
            'cue' => config('app.key')
        ];
        return $this->returnSuccess(data: $metaData, message: "Startup Data", status: 200);
    }
    
}
