<?php
// GENERATED CODE -- DO NOT EDIT!

namespace grpc\Overview;

/**
 */
class OverviewServiceClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \grpc\Overview\OverviewRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function Overview(\grpc\Overview\OverviewRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/Overview.OverviewService/Overview',
        $argument,
        ['\grpc\Overview\OverviewResponse', 'decode'],
        $metadata, $options);
    }

}
