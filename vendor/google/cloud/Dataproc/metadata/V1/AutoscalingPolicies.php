<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/dataproc/v1/autoscaling_policies.proto

namespace GPBMetadata\Google\Cloud\Dataproc\V1;

class AutoscalingPolicies
{
    public static $is_initialized = false;

    public static function initOnce() {
        $pool = \Google\Protobuf\Internal\DescriptorPool::getGeneratedPool();

        if (static::$is_initialized == true) {
          return;
        }
        \GPBMetadata\Google\Api\Annotations::initOnce();
        \GPBMetadata\Google\Api\Client::initOnce();
        \GPBMetadata\Google\Api\FieldBehavior::initOnce();
        \GPBMetadata\Google\Api\Resource::initOnce();
        \GPBMetadata\Google\Protobuf\Duration::initOnce();
        \GPBMetadata\Google\Protobuf\GPBEmpty::initOnce();
        $pool->internalAddGeneratedFile(
            '
�
3google/cloud/dataproc/v1/autoscaling_policies.protogoogle.cloud.dataproc.v1google/api/client.protogoogle/api/field_behavior.protogoogle/api/resource.protogoogle/protobuf/duration.protogoogle/protobuf/empty.proto"�
AutoscalingPolicy

id (	
name (	B�AS
basic_algorithm (23.google.cloud.dataproc.v1.BasicAutoscalingAlgorithmB�AH Z
worker_config (2>.google.cloud.dataproc.v1.InstanceGroupAutoscalingPolicyConfigB�Ad
secondary_worker_config (2>.google.cloud.dataproc.v1.InstanceGroupAutoscalingPolicyConfigB�A:��A�
)dataproc.googleapis.com/AutoscalingPolicyPprojects/{project}/locations/{location}/autoscalingPolicies/{autoscaling_policy}Lprojects/{project}/regions/{region}/autoscalingPolicies/{autoscaling_policy}B
	algorithm"�
BasicAutoscalingAlgorithmN
yarn_config (24.google.cloud.dataproc.v1.BasicYarnAutoscalingConfigB�A7
cooldown_period (2.google.protobuf.DurationB�A"�
BasicYarnAutoscalingConfigE
graceful_decommission_timeout (2.google.protobuf.DurationB�A
scale_up_factor (B�A
scale_down_factor (B�A)
scale_up_min_worker_fraction (B�A+
scale_down_min_worker_fraction (B�A"s
$InstanceGroupAutoscalingPolicyConfig
min_instances (B�A
max_instances (B�A
weight (B�A"�
CreateAutoscalingPolicyRequestA
parent (	B1�A�A+)dataproc.googleapis.com/AutoscalingPolicy@
policy (2+.google.cloud.dataproc.v1.AutoscalingPolicyB�A"^
GetAutoscalingPolicyRequest?
name (	B1�A�A+
)dataproc.googleapis.com/AutoscalingPolicy"b
UpdateAutoscalingPolicyRequest@
policy (2+.google.cloud.dataproc.v1.AutoscalingPolicyB�A"a
DeleteAutoscalingPolicyRequest?
name (	B1�A�A+
)dataproc.googleapis.com/AutoscalingPolicy"�
ListAutoscalingPoliciesRequestA
parent (	B1�A�A+)dataproc.googleapis.com/AutoscalingPolicy
	page_size (B�A

page_token (	B�A"�
ListAutoscalingPoliciesResponseB
policies (2+.google.cloud.dataproc.v1.AutoscalingPolicyB�A
next_page_token (	B�A2�
AutoscalingPolicyService�
CreateAutoscalingPolicy8.google.cloud.dataproc.v1.CreateAutoscalingPolicyRequest+.google.cloud.dataproc.v1.AutoscalingPolicy"�����"7/v1/{parent=projects/*/locations/*}/autoscalingPolicies:policyZ?"5/v1/{parent=projects/*/regions/*}/autoscalingPolicies:policy�Aparent,policy�
UpdateAutoscalingPolicy8.google.cloud.dataproc.v1.UpdateAutoscalingPolicyRequest+.google.cloud.dataproc.v1.AutoscalingPolicy"�����>/v1/{policy.name=projects/*/locations/*/autoscalingPolicies/*}:policyZF</v1/{policy.name=projects/*/regions/*/autoscalingPolicies/*}:policy�Apolicy�
GetAutoscalingPolicy5.google.cloud.dataproc.v1.GetAutoscalingPolicyRequest+.google.cloud.dataproc.v1.AutoscalingPolicy"���r7/v1/{name=projects/*/locations/*/autoscalingPolicies/*}Z75/v1/{name=projects/*/regions/*/autoscalingPolicies/*}�Aname�
ListAutoscalingPolicies8.google.cloud.dataproc.v1.ListAutoscalingPoliciesRequest9.google.cloud.dataproc.v1.ListAutoscalingPoliciesResponse"����r7/v1/{parent=projects/*/locations/*}/autoscalingPoliciesZ75/v1/{parent=projects/*/regions/*}/autoscalingPolicies�Aparent�
DeleteAutoscalingPolicy8.google.cloud.dataproc.v1.DeleteAutoscalingPolicyRequest.google.protobuf.Empty"���r*7/v1/{name=projects/*/locations/*/autoscalingPolicies/*}Z7*5/v1/{name=projects/*/regions/*/autoscalingPolicies/*}�AnameK�Adataproc.googleapis.com�A.https://www.googleapis.com/auth/cloud-platformB�
com.google.cloud.dataproc.v1BAutoscalingPoliciesProtoPZ@google.golang.org/genproto/googleapis/cloud/dataproc/v1;dataproc�AE
dataproc.googleapis.com/Region#projects/{project}/regions/{region}bproto3'
        , true);

        static::$is_initialized = true;
    }
}

