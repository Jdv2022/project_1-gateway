<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: ums.proto

namespace Users;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>users.RegisterUserDetailsResponse</code>
 */
class RegisterUserDetailsResponse extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>bool saved = 1;</code>
     */
    protected $saved = false;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type bool $saved
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Ums::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>bool saved = 1;</code>
     * @return bool
     */
    public function getSaved()
    {
        return $this->saved;
    }

    /**
     * Generated from protobuf field <code>bool saved = 1;</code>
     * @param bool $var
     * @return $this
     */
    public function setSaved($var)
    {
        GPBUtil::checkBool($var);
        $this->saved = $var;

        return $this;
    }

}

