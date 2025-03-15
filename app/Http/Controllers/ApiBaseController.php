<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;

class ApiBaseController extends Controller {

    public function __construct() {

    }

    protected function returnSuccess(mixed $data, string $message = "", array $customProperties = []):array {
        $responseFormat = [
            'status' => 'Success',
            'error' => 0,
            'message' => $message,
            'payload' => $data,
        ];
        $responseFormat = array_merge($responseFormat, $customProperties);
        return $responseFormat;
    }

}
