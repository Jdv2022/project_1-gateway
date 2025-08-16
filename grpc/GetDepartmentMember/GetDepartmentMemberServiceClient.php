<?php
// GENERATED CODE -- DO NOT EDIT!

namespace grpc\GetDepartmentMember;

/**
 */
class GetDepartmentMemberServiceClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \grpc\GetDepartmentMember\GetDepartmentMemberRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function GetDepartmentMember(\grpc\GetDepartmentMember\GetDepartmentMemberRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/GetDepartmentMember.GetDepartmentMemberService/GetDepartmentMember',
        $argument,
        ['\grpc\GetDepartmentMember\GetDepartmentMemberResponse', 'decode'],
        $metadata, $options);
    }

}
