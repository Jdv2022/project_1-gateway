<?php
// GENERATED CODE -- DO NOT EDIT!

namespace grpc\SuggestedMemberDepartment;

/**
 */
class SuggestedMemberDepartmentServiceClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \grpc\SuggestedMemberDepartment\SuggestedMemberDepartmentRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function SuggestedMemberDepartment(\grpc\SuggestedMemberDepartment\SuggestedMemberDepartmentRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/SuggestedMemberDepartment.SuggestedMemberDepartmentService/SuggestedMemberDepartment',
        $argument,
        ['\grpc\SuggestedMemberDepartment\SuggestedMemberDepartmentResponse', 'decode'],
        $metadata, $options);
    }

}
