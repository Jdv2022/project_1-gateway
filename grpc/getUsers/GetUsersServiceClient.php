<?php
// GENERATED CODE -- DO NOT EDIT!

namespace grpc\getUsers;

/**
 */
class GetUsersServiceClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \grpc\getUsers\GetUsersRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function GetUsers(\grpc\getUsers\GetUsersRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/getUsers.GetUsersService/GetUsers',
        $argument,
        ['\grpc\getUsers\GetUsersResponse', 'decode'],
        $metadata, $options);
    }

}
