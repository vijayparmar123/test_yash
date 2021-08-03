<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/asset/v1/assets.proto

namespace Google\Cloud\Asset\V1\IamPolicyAnalysisResult;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * An access control list, derived from the above IAM policy binding, which
 * contains a set of resources and accesses. May include one
 * item from each set to compose an access control entry.
 * NOTICE that there could be multiple access control lists for one IAM policy
 * binding. The access control lists are created based on resource and access
 * combinations.
 * For example, assume we have the following cases in one IAM policy binding:
 * - Permission P1 and P2 apply to resource R1 and R2;
 * - Permission P3 applies to resource R2 and R3;
 * This will result in the following access control lists:
 * - AccessControlList 1: [R1, R2], [P1, P2]
 * - AccessControlList 2: [R2, R3], [P3]
 *
 * Generated from protobuf message <code>google.cloud.asset.v1.IamPolicyAnalysisResult.AccessControlList</code>
 */
class AccessControlList extends \Google\Protobuf\Internal\Message
{
    /**
     * The resources that match one of the following conditions:
     * - The resource_selector, if it is specified in request;
     * - Otherwise, resources reachable from the policy attached resource.
     *
     * Generated from protobuf field <code>repeated .google.cloud.asset.v1.IamPolicyAnalysisResult.Resource resources = 1;</code>
     */
    private $resources;
    /**
     * The accesses that match one of the following conditions:
     * - The access_selector, if it is specified in request;
     * - Otherwise, access specifiers reachable from the policy binding's role.
     *
     * Generated from protobuf field <code>repeated .google.cloud.asset.v1.IamPolicyAnalysisResult.Access accesses = 2;</code>
     */
    private $accesses;
    /**
     * Resource edges of the graph starting from the policy attached
     * resource to any descendant resources. The [Edge.source_node][google.cloud.asset.v1.IamPolicyAnalysisResult.Edge.source_node] contains
     * the full resource name of a parent resource and [Edge.target_node][google.cloud.asset.v1.IamPolicyAnalysisResult.Edge.target_node]
     * contains the full resource name of a child resource. This field is
     * present only if the output_resource_edges option is enabled in request.
     *
     * Generated from protobuf field <code>repeated .google.cloud.asset.v1.IamPolicyAnalysisResult.Edge resource_edges = 3;</code>
     */
    private $resource_edges;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \Google\Cloud\Asset\V1\IamPolicyAnalysisResult\Resource[]|\Google\Protobuf\Internal\RepeatedField $resources
     *           The resources that match one of the following conditions:
     *           - The resource_selector, if it is specified in request;
     *           - Otherwise, resources reachable from the policy attached resource.
     *     @type \Google\Cloud\Asset\V1\IamPolicyAnalysisResult\Access[]|\Google\Protobuf\Internal\RepeatedField $accesses
     *           The accesses that match one of the following conditions:
     *           - The access_selector, if it is specified in request;
     *           - Otherwise, access specifiers reachable from the policy binding's role.
     *     @type \Google\Cloud\Asset\V1\IamPolicyAnalysisResult\Edge[]|\Google\Protobuf\Internal\RepeatedField $resource_edges
     *           Resource edges of the graph starting from the policy attached
     *           resource to any descendant resources. The [Edge.source_node][google.cloud.asset.v1.IamPolicyAnalysisResult.Edge.source_node] contains
     *           the full resource name of a parent resource and [Edge.target_node][google.cloud.asset.v1.IamPolicyAnalysisResult.Edge.target_node]
     *           contains the full resource name of a child resource. This field is
     *           present only if the output_resource_edges option is enabled in request.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Cloud\Asset\V1\Assets::initOnce();
        parent::__construct($data);
    }

    /**
     * The resources that match one of the following conditions:
     * - The resource_selector, if it is specified in request;
     * - Otherwise, resources reachable from the policy attached resource.
     *
     * Generated from protobuf field <code>repeated .google.cloud.asset.v1.IamPolicyAnalysisResult.Resource resources = 1;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * The resources that match one of the following conditions:
     * - The resource_selector, if it is specified in request;
     * - Otherwise, resources reachable from the policy attached resource.
     *
     * Generated from protobuf field <code>repeated .google.cloud.asset.v1.IamPolicyAnalysisResult.Resource resources = 1;</code>
     * @param \Google\Cloud\Asset\V1\IamPolicyAnalysisResult\Resource[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setResources($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Google\Cloud\Asset\V1\IamPolicyAnalysisResult\Resource::class);
        $this->resources = $arr;

        return $this;
    }

    /**
     * The accesses that match one of the following conditions:
     * - The access_selector, if it is specified in request;
     * - Otherwise, access specifiers reachable from the policy binding's role.
     *
     * Generated from protobuf field <code>repeated .google.cloud.asset.v1.IamPolicyAnalysisResult.Access accesses = 2;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getAccesses()
    {
        return $this->accesses;
    }

    /**
     * The accesses that match one of the following conditions:
     * - The access_selector, if it is specified in request;
     * - Otherwise, access specifiers reachable from the policy binding's role.
     *
     * Generated from protobuf field <code>repeated .google.cloud.asset.v1.IamPolicyAnalysisResult.Access accesses = 2;</code>
     * @param \Google\Cloud\Asset\V1\IamPolicyAnalysisResult\Access[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setAccesses($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Google\Cloud\Asset\V1\IamPolicyAnalysisResult\Access::class);
        $this->accesses = $arr;

        return $this;
    }

    /**
     * Resource edges of the graph starting from the policy attached
     * resource to any descendant resources. The [Edge.source_node][google.cloud.asset.v1.IamPolicyAnalysisResult.Edge.source_node] contains
     * the full resource name of a parent resource and [Edge.target_node][google.cloud.asset.v1.IamPolicyAnalysisResult.Edge.target_node]
     * contains the full resource name of a child resource. This field is
     * present only if the output_resource_edges option is enabled in request.
     *
     * Generated from protobuf field <code>repeated .google.cloud.asset.v1.IamPolicyAnalysisResult.Edge resource_edges = 3;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getResourceEdges()
    {
        return $this->resource_edges;
    }

    /**
     * Resource edges of the graph starting from the policy attached
     * resource to any descendant resources. The [Edge.source_node][google.cloud.asset.v1.IamPolicyAnalysisResult.Edge.source_node] contains
     * the full resource name of a parent resource and [Edge.target_node][google.cloud.asset.v1.IamPolicyAnalysisResult.Edge.target_node]
     * contains the full resource name of a child resource. This field is
     * present only if the output_resource_edges option is enabled in request.
     *
     * Generated from protobuf field <code>repeated .google.cloud.asset.v1.IamPolicyAnalysisResult.Edge resource_edges = 3;</code>
     * @param \Google\Cloud\Asset\V1\IamPolicyAnalysisResult\Edge[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setResourceEdges($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Google\Cloud\Asset\V1\IamPolicyAnalysisResult\Edge::class);
        $this->resource_edges = $arr;

        return $this;
    }

}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(AccessControlList::class, \Google\Cloud\Asset\V1\IamPolicyAnalysisResult_AccessControlList::class);

