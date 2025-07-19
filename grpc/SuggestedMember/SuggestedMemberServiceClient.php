<?php
// GENERATED CODE -- DO NOT EDIT!

namespace grpc\SuggestedMember;

/**
 */
class SuggestedMemberServiceClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \grpc\SuggestedMember\SuggestedMemberRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function SuggestedMember(\grpc\SuggestedMember\SuggestedMemberRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/SuggestedMember.SuggestedMemberService/SuggestedMember',
        $argument,
        ['\grpc\SuggestedMember\SuggestedMemberResponse', 'decode'],
        $metadata, $options);
    }

}
