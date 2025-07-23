<?php
// GENERATED CODE -- DO NOT EDIT!

namespace grpc\DeleteTeam;

/**
 */
class DeleteTeamServiceClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \grpc\DeleteTeam\DeleteTeamRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function DeleteTeam(\grpc\DeleteTeam\DeleteTeamRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/DeleteTeam.DeleteTeamService/DeleteTeam',
        $argument,
        ['\grpc\DeleteTeam\DeleteTeamResponse', 'decode'],
        $metadata, $options);
    }

}
