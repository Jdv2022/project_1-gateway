<?php
// GENERATED CODE -- DO NOT EDIT!

namespace grpc\EditTeam;

/**
 */
class EditTeamServiceClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \grpc\EditTeam\EditTeamRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function EditTeam(\grpc\EditTeam\EditTeamRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/EditTeam.EditTeamService/EditTeam',
        $argument,
        ['\grpc\EditTeam\EditTeamResponse', 'decode'],
        $metadata, $options);
    }

}
