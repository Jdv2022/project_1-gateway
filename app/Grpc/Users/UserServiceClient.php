<?php
// GENERATED CODE -- DO NOT EDIT!

namespace Users;

/**
 */
class UserServiceClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \Users\RegisterUserDetailsRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function RegisterUserDetails(\Users\RegisterUserDetailsRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/users.UserService/RegisterUserDetails',
        $argument,
        ['\Users\RegisterUserDetailsResponse', 'decode'],
        $metadata, $options);
    }

}
