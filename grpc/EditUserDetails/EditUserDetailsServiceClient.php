<?php
// GENERATED CODE -- DO NOT EDIT!

namespace grpc\EditUserDetails;

/**
 */
class EditUserDetailsServiceClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \grpc\EditUserDetails\EditUserDetailsRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function EditUserDetails(\grpc\EditUserDetails\EditUserDetailsRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/EditUserDetails.EditUserDetailsService/EditUserDetails',
        $argument,
        ['\grpc\EditUserDetails\EditUserDetailsResponse', 'decode'],
        $metadata, $options);
    }

}
