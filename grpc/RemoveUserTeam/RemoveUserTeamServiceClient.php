<?php
// GENERATED CODE -- DO NOT EDIT!

namespace grpc\RemoveUserTeam;

/**
 */
class RemoveUserTeamServiceClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \grpc\RemoveUserTeam\RemoveUserTeamRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function RemoveUserTeam(\grpc\RemoveUserTeam\RemoveUserTeamRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/RemoveUserTeam.RemoveUserTeamService/RemoveUserTeam',
        $argument,
        ['\grpc\RemoveUserTeam\RemoveUserTeamResponse', 'decode'],
        $metadata, $options);
    }

}
