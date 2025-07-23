<?php
// GENERATED CODE -- DO NOT EDIT!

namespace grpc\GetDepartmentDetail;

/**
 */
class GetDepartmentDetailServiceClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \grpc\GetDepartmentDetail\GetDepartmentDetailRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function GetDepartmentDetail(\grpc\GetDepartmentDetail\GetDepartmentDetailRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/GetDepartmentDetail.GetDepartmentDetailService/GetDepartmentDetail',
        $argument,
        ['\grpc\GetDepartmentDetail\GetDepartmentDetailResponse', 'decode'],
        $metadata, $options);
    }

}
