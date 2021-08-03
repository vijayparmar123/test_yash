<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/webrisk/v1beta1/webrisk.proto

namespace Google\Cloud\WebRisk\V1beta1\ComputeThreatListDiffResponse;

use UnexpectedValueException;

/**
 * The type of response sent to the client.
 *
 * Protobuf type <code>google.cloud.webrisk.v1beta1.ComputeThreatListDiffResponse.ResponseType</code>
 */
class ResponseType
{
    /**
     * Unknown.
     *
     * Generated from protobuf enum <code>RESPONSE_TYPE_UNSPECIFIED = 0;</code>
     */
    const RESPONSE_TYPE_UNSPECIFIED = 0;
    /**
     * Partial updates are applied to the client's existing local database.
     *
     * Generated from protobuf enum <code>DIFF = 1;</code>
     */
    const DIFF = 1;
    /**
     * Full updates resets the client's entire local database. This means
     * that either the client had no state, was seriously out-of-date,
     * or the client is believed to be corrupt.
     *
     * Generated from protobuf enum <code>RESET = 2;</code>
     */
    const RESET = 2;

    private static $valueToName = [
        self::RESPONSE_TYPE_UNSPECIFIED => 'RESPONSE_TYPE_UNSPECIFIED',
        self::DIFF => 'DIFF',
        self::RESET => 'RESET',
    ];

    public static function name($value)
    {
        if (!isset(self::$valueToName[$value])) {
            throw new UnexpectedValueException(sprintf(
                    'Enum %s has no name defined for value %s', __CLASS__, $value));
        }
        return self::$valueToName[$value];
    }


    public static function value($name)
    {
        $const = __CLASS__ . '::' . strtoupper($name);
        if (!defined($const)) {
            throw new UnexpectedValueException(sprintf(
                    'Enum %s has no value defined for name %s', __CLASS__, $name));
        }
        return constant($const);
    }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ResponseType::class, \Google\Cloud\WebRisk\V1beta1\ComputeThreatListDiffResponse_ResponseType::class);

