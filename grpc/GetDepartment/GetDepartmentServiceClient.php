<?php
// GENERATED CODE -- DO NOT EDIT!

namespace grpc\GetDepartment;

/**
 */
class GetDepartmentServiceClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \grpc\GetDepartment\GetDepartmentRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function GetDepartment(\grpc\GetDepartment\GetDepartmentRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/GetDepartment.GetDepartmentService/GetDepartment',
        $argument,
        ['\grpc\GetDepartment\GetDepartmentResponse', 'decode'],
        $metadata, $options);
    }

}
