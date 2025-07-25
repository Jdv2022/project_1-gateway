<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: ums/overview.proto

namespace grpc\Overview;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>Overview.OverviewResponse</code>
 */
class OverviewResponse extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>repeated .Overview.UserAccessCounter userAccessCounter = 1;</code>
     */
    private $userAccessCounter;
    /**
     * Generated from protobuf field <code>repeated .Overview.UserDetail userDetail = 2;</code>
     */
    private $userDetail;
    /**
     * Generated from protobuf field <code>repeated .Overview.UserDetailUserRole userDetailUserRole = 3;</code>
     */
    private $userDetailUserRole;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \grpc\Overview\UserAccessCounter[]|\Google\Protobuf\Internal\RepeatedField $userAccessCounter
     *     @type \grpc\Overview\UserDetail[]|\Google\Protobuf\Internal\RepeatedField $userDetail
     *     @type \grpc\Overview\UserDetailUserRole[]|\Google\Protobuf\Internal\RepeatedField $userDetailUserRole
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Ums\Overview::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>repeated .Overview.UserAccessCounter userAccessCounter = 1;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getUserAccessCounter()
    {
        return $this->userAccessCounter;
    }

    /**
     * Generated from protobuf field <code>repeated .Overview.UserAccessCounter userAccessCounter = 1;</code>
     * @param \grpc\Overview\UserAccessCounter[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setUserAccessCounter($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \grpc\Overview\UserAccessCounter::class);
        $this->userAccessCounter = $arr;

        return $this;
    }

    /**
     * Generated from protobuf field <code>repeated .Overview.UserDetail userDetail = 2;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getUserDetail()
    {
        return $this->userDetail;
    }

    /**
     * Generated from protobuf field <code>repeated .Overview.UserDetail userDetail = 2;</code>
     * @param \grpc\Overview\UserDetail[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setUserDetail($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \grpc\Overview\UserDetail::class);
        $this->userDetail = $arr;

        return $this;
    }

    /**
     * Generated from protobuf field <code>repeated .Overview.UserDetailUserRole userDetailUserRole = 3;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getUserDetailUserRole()
    {
        return $this->userDetailUserRole;
    }

    /**
     * Generated from protobuf field <code>repeated .Overview.UserDetailUserRole userDetailUserRole = 3;</code>
     * @param \grpc\Overview\UserDetailUserRole[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setUserDetailUserRole($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \grpc\Overview\UserDetailUserRole::class);
        $this->userDetailUserRole = $arr;

        return $this;
    }

}

