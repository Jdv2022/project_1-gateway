<?php
// GENERATED CODE -- DO NOT EDIT!

namespace grpc\CreateTeam;

/**
 */
class CreateTeamServiceClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \grpc\CreateTeam\CreateTeamRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function CreateTeam(\grpc\CreateTeam\CreateTeamRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/CreateTeam.CreateTeamService/CreateTeam',
        $argument,
        ['\grpc\CreateTeam\CreateTeamResponse', 'decode'],
        $metadata, $options);
    }

}
