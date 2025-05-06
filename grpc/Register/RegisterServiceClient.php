<?php
// GENERATED CODE -- DO NOT EDIT!

namespace grpc\Register;

/**
 */
class RegisterServiceClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \grpc\Register\RegisterUserDetailsRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function RegisterUserDetails(\grpc\Register\RegisterUserDetailsRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/register.RegisterService/RegisterUserDetails',
        $argument,
        ['\grpc\Register\RegisterUserDetailsResponse', 'decode'],
        $metadata, $options);
    }

}
