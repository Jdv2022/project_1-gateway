<?php
// GENERATED CODE -- DO NOT EDIT!

namespace grpc\TeamUsersLists;

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
     * @param \grpc\TeamUsersLists\TeamUsersListsRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function TeamUsersLists(\grpc\TeamUsersLists\TeamUsersListsRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/TeamUsersLists.TeamListsService/TeamUsersLists',
        $argument,
        ['\grpc\TeamUsersLists\TeamUsersListsResponse', 'decode'],
        $metadata, $options);
    }

}
