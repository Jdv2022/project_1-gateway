<?php
// GENERATED CODE -- DO NOT EDIT!

namespace grpc\userClockIn;

/**
 */
class UserClockInServiceClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \grpc\userClockIn\UserClockInRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function UserClockInService(\grpc\userClockIn\UserClockInRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/userClockIn.UserClockInService/UserClockInService',
        $argument,
        ['\grpc\userClockIn\UserClockInResponse', 'decode'],
        $metadata, $options);
    }

}
