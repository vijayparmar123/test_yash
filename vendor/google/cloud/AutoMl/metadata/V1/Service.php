<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/automl/v1/service.proto

namespace GPBMetadata\Google\Cloud\Automl\V1;

class Service
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
        \GPBMetadata\Google\Cloud\Automl\V1\AnnotationPayload::initOnce();
        \GPBMetadata\Google\Cloud\Automl\V1\AnnotationSpec::initOnce();
        \GPBMetadata\Google\Cloud\Automl\V1\Dataset::initOnce();
        \GPBMetadata\Google\Cloud\Automl\V1\Image::initOnce();
        \GPBMetadata\Google\Cloud\Automl\V1\Io::initOnce();
        \GPBMetadata\Google\Cloud\Automl\V1\Model::initOnce();
        \GPBMetadata\Google\Cloud\Automl\V1\ModelEvaluation::initOnce();
        \GPBMetadata\Google\Cloud\Automl\V1\Operations::initOnce();
        \GPBMetadata\Google\Longrunning\Operations::initOnce();
        \GPBMetadata\Google\Protobuf\FieldMask::initOnce();
        $pool->internalAddGeneratedFile(
            '
�5
$google/cloud/automl/v1/service.protogoogle.cloud.automl.v1google/api/client.protogoogle/api/field_behavior.protogoogle/api/resource.proto/google/cloud/automl/v1/annotation_payload.proto,google/cloud/automl/v1/annotation_spec.proto$google/cloud/automl/v1/dataset.proto"google/cloud/automl/v1/image.protogoogle/cloud/automl/v1/io.proto"google/cloud/automl/v1/model.proto-google/cloud/automl/v1/model_evaluation.proto\'google/cloud/automl/v1/operations.proto#google/longrunning/operations.proto google/protobuf/field_mask.proto"�
CreateDatasetRequest9
parent (	B)�A�A#
!locations.googleapis.com/Location5
dataset (2.google.cloud.automl.v1.DatasetB�A"H
GetDatasetRequest3
name (	B%�A�A
automl.googleapis.com/Dataset"�
ListDatasetsRequest9
parent (	B)�A�A#
!locations.googleapis.com/Location
filter (	
	page_size (

page_token (	"b
ListDatasetsResponse1
datasets (2.google.cloud.automl.v1.Dataset
next_page_token (	"�
UpdateDatasetRequest5
dataset (2.google.cloud.automl.v1.DatasetB�A4
update_mask (2.google.protobuf.FieldMaskB�A"K
DeleteDatasetRequest3
name (	B%�A�A
automl.googleapis.com/Dataset"�
ImportDataRequest3
name (	B%�A�A
automl.googleapis.com/Dataset>
input_config (2#.google.cloud.automl.v1.InputConfigB�A"�
ExportDataRequest3
name (	B%�A�A
automl.googleapis.com/Dataset@
output_config (2$.google.cloud.automl.v1.OutputConfigB�A"V
GetAnnotationSpecRequest:
name (	B,�A�A&
$automl.googleapis.com/AnnotationSpec"�
CreateModelRequest9
parent (	B)�A�A#
!locations.googleapis.com/Location1
model (2.google.cloud.automl.v1.ModelB�A"D
GetModelRequest1
name (	B#�A�A
automl.googleapis.com/Model"�
ListModelsRequest9
parent (	B)�A�A#
!locations.googleapis.com/Location
filter (	
	page_size (

page_token (	"[
ListModelsResponse,
model (2.google.cloud.automl.v1.Model
next_page_token (	"G
DeleteModelRequest1
name (	B#�A�A
automl.googleapis.com/Model"}
UpdateModelRequest1
model (2.google.cloud.automl.v1.ModelB�A4
update_mask (2.google.protobuf.FieldMaskB�A"�
DeployModelRequest
0image_object_detection_model_deployment_metadata (2C.google.cloud.automl.v1.ImageObjectDetectionModelDeploymentMetadataH |
.image_classification_model_deployment_metadata (2B.google.cloud.automl.v1.ImageClassificationModelDeploymentMetadataH 1
name (	B#�A�A
automl.googleapis.com/ModelB
model_deployment_metadata"I
UndeployModelRequest1
name (	B#�A�A
automl.googleapis.com/Model"�
ExportModelRequest1
name (	B#�A�A
automl.googleapis.com/ModelK
output_config (2/.google.cloud.automl.v1.ModelExportOutputConfigB�A"X
GetModelEvaluationRequest;
name (	B-�A�A\'
%automl.googleapis.com/ModelEvaluation"�
ListModelEvaluationsRequest3
parent (	B#�A�A
automl.googleapis.com/Model
filter (	B�A
	page_size (

page_token (	"z
ListModelEvaluationsResponseA
model_evaluation (2\'.google.cloud.automl.v1.ModelEvaluation
next_page_token (	2�
AutoMl�
CreateDataset,.google.cloud.automl.v1.CreateDatasetRequest.google.longrunning.Operation"m���7",/v1/{parent=projects/*/locations/*}/datasets:dataset�Aparent,dataset�A
DatasetOperationMetadata�

GetDataset).google.cloud.automl.v1.GetDatasetRequest.google.cloud.automl.v1.Dataset";���.,/v1/{name=projects/*/locations/*/datasets/*}�Aname�
ListDatasets+.google.cloud.automl.v1.ListDatasetsRequest,.google.cloud.automl.v1.ListDatasetsResponse"=���.,/v1/{parent=projects/*/locations/*}/datasets�Aparent�
UpdateDataset,.google.cloud.automl.v1.UpdateDatasetRequest.google.cloud.automl.v1.Dataset"[���?24/v1/{dataset.name=projects/*/locations/*/datasets/*}:dataset�Adataset,update_mask�
DeleteDataset,.google.cloud.automl.v1.DeleteDatasetRequest.google.longrunning.Operation"h���.*,/v1/{name=projects/*/locations/*/datasets/*}�Aname�A*
google.protobuf.EmptyOperationMetadata�

ImportData).google.cloud.automl.v1.ImportDataRequest.google.longrunning.Operation"����<"7/v1/{name=projects/*/locations/*/datasets/*}:importData:*�Aname,input_config�A*
google.protobuf.EmptyOperationMetadata�

ExportData).google.cloud.automl.v1.ExportDataRequest.google.longrunning.Operation"����<"7/v1/{name=projects/*/locations/*/datasets/*}:exportData:*�Aname,output_config�A*
google.protobuf.EmptyOperationMetadata�
GetAnnotationSpec0.google.cloud.automl.v1.GetAnnotationSpecRequest&.google.cloud.automl.v1.AnnotationSpec"M���@>/v1/{name=projects/*/locations/*/datasets/*/annotationSpecs/*}�Aname�
CreateModel*.google.cloud.automl.v1.CreateModelRequest.google.longrunning.Operation"e���3"*/v1/{parent=projects/*/locations/*}/models:model�Aparent,model�A
ModelOperationMetadata�
GetModel\'.google.cloud.automl.v1.GetModelRequest.google.cloud.automl.v1.Model"9���,*/v1/{name=projects/*/locations/*/models/*}�Aname�

ListModels).google.cloud.automl.v1.ListModelsRequest*.google.cloud.automl.v1.ListModelsResponse";���,*/v1/{parent=projects/*/locations/*}/models�Aparent�
DeleteModel*.google.cloud.automl.v1.DeleteModelRequest.google.longrunning.Operation"f���,**/v1/{name=projects/*/locations/*/models/*}�Aname�A*
google.protobuf.EmptyOperationMetadata�
UpdateModel*.google.cloud.automl.v1.UpdateModelRequest.google.cloud.automl.v1.Model"S���920/v1/{model.name=projects/*/locations/*/models/*}:model�Amodel,update_mask�
DeployModel*.google.cloud.automl.v1.DeployModelRequest.google.longrunning.Operation"p���6"1/v1/{name=projects/*/locations/*/models/*}:deploy:*�Aname�A*
google.protobuf.EmptyOperationMetadata�
UndeployModel,.google.cloud.automl.v1.UndeployModelRequest.google.longrunning.Operation"r���8"3/v1/{name=projects/*/locations/*/models/*}:undeploy:*�Aname�A*
google.protobuf.EmptyOperationMetadata�
ExportModel*.google.cloud.automl.v1.ExportModelRequest.google.longrunning.Operation"~���6"1/v1/{name=projects/*/locations/*/models/*}:export:*�Aname,output_config�A*
google.protobuf.EmptyOperationMetadata�
GetModelEvaluation1.google.cloud.automl.v1.GetModelEvaluationRequest\'.google.cloud.automl.v1.ModelEvaluation"L���?=/v1/{name=projects/*/locations/*/models/*/modelEvaluations/*}�Aname�
ListModelEvaluations3.google.cloud.automl.v1.ListModelEvaluationsRequest4.google.cloud.automl.v1.ListModelEvaluationsResponse"U���?=/v1/{parent=projects/*/locations/*/models/*}/modelEvaluations�Aparent,filterI�Aautoml.googleapis.com�A.https://www.googleapis.com/auth/cloud-platformB�
com.google.cloud.automl.v1BAutoMlProtoPZ<google.golang.org/genproto/googleapis/cloud/automl/v1;automl�Google.Cloud.AutoML.V1�Google\\Cloud\\AutoMl\\V1�Google::Cloud::AutoML::V1bproto3'
        , true);

        static::$is_initialized = true;
    }
}

