<?php
// GENERATED CODE -- DO NOT EDIT!

namespace grpc\userRegistrationFormData;

/**
 */
class UserRegistrationFormDataServiceClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \grpc\userRegistrationFormData\UserRegistrationFormDataRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function UserRegistrationFormData(\grpc\userRegistrationFormData\UserRegistrationFormDataRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/userRegistrationFormData.UserRegistrationFormDataService/UserRegistrationFormData',
        $argument,
        ['\grpc\userRegistrationFormData\UserRegistrationFormDataResponse', 'decode'],
        $metadata, $options);
    }

}
