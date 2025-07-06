<?php
// GENERATED CODE -- DO NOT EDIT!

namespace grpc\GetArchives;

/**
 */
class GetArchivesServiceClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \grpc\GetArchives\GetArchivesRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function GetArchives(\grpc\GetArchives\GetArchivesRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/GetArchives.GetArchivesService/GetArchives',
        $argument,
        ['\grpc\GetArchives\GetArchivesResponse', 'decode'],
        $metadata, $options);
    }

}
