<?php
// GENERATED CODE -- DO NOT EDIT!

namespace grpc\CreateShift;

/**
 */
class CreateShiftServiceClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \grpc\CreateShift\CreateShiftRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function CreateShift(\grpc\CreateShift\CreateShiftRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/CreateShift.CreateShiftService/CreateShift',
        $argument,
        ['\grpc\CreateShift\CreateShiftResponse', 'decode'],
        $metadata, $options);
    }

}
