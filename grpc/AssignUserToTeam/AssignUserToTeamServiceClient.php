<?php
// GENERATED CODE -- DO NOT EDIT!

namespace grpc\AssignUserToTeam;

/**
 */
class AssignUserToTeamServiceClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \grpc\AssignUserToTeam\AssignUserToTeamRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function AssignUser(\grpc\AssignUserToTeam\AssignUserToTeamRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/AssignUserToTeam.AssignUserToTeamService/AssignUser',
        $argument,
        ['\grpc\AssignUserToTeam\AssignUserToTeamResponse', 'decode'],
        $metadata, $options);
    }

}
