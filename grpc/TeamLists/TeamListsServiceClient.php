<?php
// GENERATED CODE -- DO NOT EDIT!

namespace grpc\TeamLists;

/**
 */
class TeamListsServiceClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \grpc\TeamLists\TeamListsRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function TeamLists(\grpc\TeamLists\TeamListsRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/TeamLists.TeamListsService/TeamLists',
        $argument,
        ['\grpc\TeamLists\TeamListsResponse', 'decode'],
        $metadata, $options);
    }

}
