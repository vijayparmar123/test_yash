<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/bigquery/storage/v1/arrow.proto

namespace GPBMetadata\Google\Cloud\Bigquery\Storage\V1;

class Arrow
{
    public static $is_initialized = false;

    public static function initOnce() {
        $pool = \Google\Protobuf\Internal\DescriptorPool::getGeneratedPool();

        if (static::$is_initialized == true) {
          return;
        }
        $pool->internalAddGeneratedFile(
            '
�
,google/cloud/bigquery/storage/v1/arrow.proto google.cloud.bigquery.storage.v1"(
ArrowSchema
serialized_schema ("F
ArrowRecordBatch
serialized_record_batch (
	row_count ("�
ArrowSerializationOptionsh
buffer_compression (2L.google.cloud.bigquery.storage.v1.ArrowSerializationOptions.CompressionCodec"H
CompressionCodec
COMPRESSION_UNSPECIFIED 
	LZ4_FRAME
ZSTDB�
$com.google.cloud.bigquery.storage.v1B
ArrowProtoPZGgoogle.golang.org/genproto/googleapis/cloud/bigquery/storage/v1;storage� Google.Cloud.BigQuery.Storage.V1� Google\\Cloud\\BigQuery\\Storage\\V1bproto3'
        , true);

        static::$is_initialized = true;
    }
}

