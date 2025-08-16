<?php
// GENERATED CODE -- DO NOT EDIT!

namespace grpc\DeleteDepartment;

/**
 */
class DeleteDepartmentServiceClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \grpc\DeleteDepartment\DeleteDepartmentRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function DeleteDepartment(\grpc\DeleteDepartment\DeleteDepartmentRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/DeleteDepartment.DeleteDepartmentService/DeleteDepartment',
        $argument,
        ['\grpc\DeleteDepartment\DeleteDepartmentResponse', 'decode'],
        $metadata, $options);
    }

}
