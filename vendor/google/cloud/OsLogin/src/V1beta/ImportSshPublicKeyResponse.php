<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/oslogin/v1beta/oslogin.proto

namespace Google\Cloud\OsLogin\V1beta;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * A response message for importing an SSH public key.
 *
 * Generated from protobuf message <code>google.cloud.oslogin.v1beta.ImportSshPublicKeyResponse</code>
 */
class ImportSshPublicKeyResponse extends \Google\Protobuf\Internal\Message
{
    /**
     * The login profile information for the user.
     *
     * Generated from protobuf field <code>.google.cloud.oslogin.v1beta.LoginProfile login_profile = 1;</code>
     */
    private $login_profile = null;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \Google\Cloud\OsLogin\V1beta\LoginProfile $login_profile
     *           The login profile information for the user.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Cloud\Oslogin\V1Beta\Oslogin::initOnce();
        parent::__construct($data);
    }

    /**
     * The login profile information for the user.
     *
     * Generated from protobuf field <code>.google.cloud.oslogin.v1beta.LoginProfile login_profile = 1;</code>
     * @return \Google\Cloud\OsLogin\V1beta\LoginProfile|null
     */
    public function getLoginProfile()
    {
        return isset($this->login_profile) ? $this->login_profile : null;
    }

    public function hasLoginProfile()
    {
        return isset($this->login_profile);
    }

    public function clearLoginProfile()
    {
        unset($this->login_profile);
    }

    /**
     * The login profile information for the user.
     *
     * Generated from protobuf field <code>.google.cloud.oslogin.v1beta.LoginProfile login_profile = 1;</code>
     * @param \Google\Cloud\OsLogin\V1beta\LoginProfile $var
     * @return $this
     */
    public function setLoginProfile($var)
    {
        GPBUtil::checkMessage($var, \Google\Cloud\OsLogin\V1beta\LoginProfile::class);
        $this->login_profile = $var;

        return $this;
    }

}

