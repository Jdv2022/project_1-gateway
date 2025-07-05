<?php
// GENERATED CODE -- DO NOT EDIT!

namespace grpc\AssignUserShift;

/**
 */
class AssignUserShiftServiceClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \grpc\AssignUserShift\AssignUserShiftRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function AssignUserShift(\grpc\AssignUserShift\AssignUserShiftRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/AssignUserShift.AssignUserShiftService/AssignUserShift',
        $argument,
        ['\grpc\AssignUserShift\AssignUserShiftResponse', 'decode'],
        $metadata, $options);
    }

}
