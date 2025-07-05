<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Log;
use Illuminate\Http\JsonResponse; 

class __ApiBaseController extends Controller {

    public function __construct() {

    }

    protected function returnSuccess(mixed $data, string $message = "", $status = 200, bool $isEnc = true):JsonResponse {
        $responseFormat = [
            'status' => 'Success',
            'error' => 0,
            'message' => $message,
			'isEncrypted' => $isEnc,
            'payload' => $data,
        ];
        return response()->json($responseFormat, $status);
    }

    protected function returnFail(mixed $data, string $message = "", $status = 500, bool $isEnc = true):JsonResponse {
        $responseFormat = [
            'status' => 'Failed',
            'error' => 1,
            'message' => $message,
			'isEncrypted' => $isEnc,
            'payload' => $data,
        ];
        return response()->json($responseFormat, $status);
	}
	

}
