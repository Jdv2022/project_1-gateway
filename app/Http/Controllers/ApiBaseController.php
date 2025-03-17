<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Log;
use Illuminate\Http\JsonResponse; 

class ApiBaseController extends Controller {

    public function __construct() {

    }

    protected function returnSuccess(mixed $data, string $message = "", $status = 200, array $customProperties = []):JsonResponse {
        $responseFormat = [
            'status' => 'Success',
            'error' => 0,
            'message' => $message,
            'payload' => $data,
        ];
        $responseFormat = array_merge($responseFormat, $customProperties);
        return response()->json($responseFormat, $status);
    }

    protected function returnFail(mixed $data, string $message = "", $status = 500, array $customProperties = []):JsonResponse {
        $responseFormat = [
            'status' => 'Failed',
            'error' => 1,
            'message' => $message,
            'payload' => $data,
        ];
        $responseFormat = array_merge($responseFormat, $customProperties);
        return response()->json($responseFormat, $status);

    }

}
