<?php
// GENERATED CODE -- DO NOT EDIT!

namespace grpc\AddArchive;

/**
 */
class AddArchiveServiceClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \grpc\AddArchive\AddArchiveRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function AddArchive(\grpc\AddArchive\AddArchiveRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/AddArchive.AddArchiveService/AddArchive',
        $argument,
        ['\grpc\AddArchive\AddArchiveResponse', 'decode'],
        $metadata, $options);
    }

}
