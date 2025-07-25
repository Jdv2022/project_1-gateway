<?php
// GENERATED CODE -- DO NOT EDIT!

namespace grpc\CreateDepartment;

/**
 */
class CreateDepartmentServiceClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \grpc\CreateDepartment\CreateDepartmentRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function CreateDepartment(\grpc\CreateDepartment\CreateDepartmentRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/CreateDepartment.CreateDepartmentService/CreateDepartment',
        $argument,
        ['\grpc\CreateDepartment\CreateDepartmentResponse', 'decode'],
        $metadata, $options);
    }

}
