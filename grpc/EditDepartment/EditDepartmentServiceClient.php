<?php
// GENERATED CODE -- DO NOT EDIT!

namespace grpc\EditDepartment;

/**
 */
class EditDepartmentServiceClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \grpc\EditDepartment\EditDepartmentRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function EditDepartment(\grpc\EditDepartment\EditDepartmentRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/EditDepartment.EditDepartmentService/EditDepartment',
        $argument,
        ['\grpc\EditDepartment\EditDepartmentResponse', 'decode'],
        $metadata, $options);
    }

}
