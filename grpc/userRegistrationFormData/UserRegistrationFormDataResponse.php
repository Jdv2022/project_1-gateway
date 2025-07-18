<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: ums/userRegistrationFormData.proto

namespace grpc\userRegistrationFormData;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>userRegistrationFormData.UserRegistrationFormDataResponse</code>
 */
class UserRegistrationFormDataResponse extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>string departments = 1;</code>
     */
    protected $departments = '';
    /**
     * Generated from protobuf field <code>string roles = 2;</code>
     */
    protected $roles = '';
    /**
     * Generated from protobuf field <code>string timezone = 3;</code>
     */
    protected $timezone = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $departments
     *     @type string $roles
     *     @type string $timezone
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Ums\UserRegistrationFormData::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>string departments = 1;</code>
     * @return string
     */
    public function getDepartments()
    {
        return $this->departments;
    }

    /**
     * Generated from protobuf field <code>string departments = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setDepartments($var)
    {
        GPBUtil::checkString($var, True);
        $this->departments = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string roles = 2;</code>
     * @return string
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Generated from protobuf field <code>string roles = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setRoles($var)
    {
        GPBUtil::checkString($var, True);
        $this->roles = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string timezone = 3;</code>
     * @return string
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * Generated from protobuf field <code>string timezone = 3;</code>
     * @param string $var
     * @return $this
     */
    public function setTimezone($var)
    {
        GPBUtil::checkString($var, True);
        $this->timezone = $var;

        return $this;
    }

}

