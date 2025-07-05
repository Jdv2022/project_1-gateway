<?php

namespace App\Http\Contracts;

class RequestModel {

    public bool $isEncrypted;
    public mixed $payload;

    public function __construct(array $data) {
        $this->isEncrypted = $data['isEncrypted'];
        $this->payload = $data['payload'];
    }

}