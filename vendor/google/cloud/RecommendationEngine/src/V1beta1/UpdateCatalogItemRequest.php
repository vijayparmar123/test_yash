<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/recommendationengine/v1beta1/catalog_service.proto

namespace Google\Cloud\RecommendationEngine\V1beta1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Request message for UpdateCatalogItem method.
 *
 * Generated from protobuf message <code>google.cloud.recommendationengine.v1beta1.UpdateCatalogItemRequest</code>
 */
class UpdateCatalogItemRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Required. Full resource name of catalog item, such as
     * "projects/&#42;&#47;locations/global/catalogs/default_catalog/catalogItems/some_catalog_item_id".
     *
     * Generated from protobuf field <code>string name = 1 [(.google.api.field_behavior) = REQUIRED];</code>
     */
    private $name = '';
    /**
     * Required. The catalog item to update/create. The 'catalog_item_id' field
     * has to match that in the 'name'.
     *
     * Generated from protobuf field <code>.google.cloud.recommendationengine.v1beta1.CatalogItem catalog_item = 2 [(.google.api.field_behavior) = REQUIRED];</code>
     */
    private $catalog_item = null;
    /**
     * Optional. Indicates which fields in the provided 'item' to update. If not
     * set, will by default update all fields.
     *
     * Generated from protobuf field <code>.google.protobuf.FieldMask update_mask = 3 [(.google.api.field_behavior) = OPTIONAL];</code>
     */
    private $update_mask = null;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $name
     *           Required. Full resource name of catalog item, such as
     *           "projects/&#42;&#47;locations/global/catalogs/default_catalog/catalogItems/some_catalog_item_id".
     *     @type \Google\Cloud\RecommendationEngine\V1beta1\CatalogItem $catalog_item
     *           Required. The catalog item to update/create. The 'catalog_item_id' field
     *           has to match that in the 'name'.
     *     @type \Google\Protobuf\FieldMask $update_mask
     *           Optional. Indicates which fields in the provided 'item' to update. If not
     *           set, will by default update all fields.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Cloud\Recommendationengine\V1Beta1\CatalogService::initOnce();
        parent::__construct($data);
    }

    /**
     * Required. Full resource name of catalog item, such as
     * "projects/&#42;&#47;locations/global/catalogs/default_catalog/catalogItems/some_catalog_item_id".
     *
     * Generated from protobuf field <code>string name = 1 [(.google.api.field_behavior) = REQUIRED];</code>
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Required. Full resource name of catalog item, such as
     * "projects/&#42;&#47;locations/global/catalogs/default_catalog/catalogItems/some_catalog_item_id".
     *
     * Generated from protobuf field <code>string name = 1 [(.google.api.field_behavior) = REQUIRED];</code>
     * @param string $var
     * @return $this
     */
    public function setName($var)
    {
        GPBUtil::checkString($var, True);
        $this->name = $var;

        return $this;
    }

    /**
     * Required. The catalog item to update/create. The 'catalog_item_id' field
     * has to match that in the 'name'.
     *
     * Generated from protobuf field <code>.google.cloud.recommendationengine.v1beta1.CatalogItem catalog_item = 2 [(.google.api.field_behavior) = REQUIRED];</code>
     * @return \Google\Cloud\RecommendationEngine\V1beta1\CatalogItem
     */
    public function getCatalogItem()
    {
        return $this->catalog_item;
    }

    /**
     * Required. The catalog item to update/create. The 'catalog_item_id' field
     * has to match that in the 'name'.
     *
     * Generated from protobuf field <code>.google.cloud.recommendationengine.v1beta1.CatalogItem catalog_item = 2 [(.google.api.field_behavior) = REQUIRED];</code>
     * @param \Google\Cloud\RecommendationEngine\V1beta1\CatalogItem $var
     * @return $this
     */
    public function setCatalogItem($var)
    {
        GPBUtil::checkMessage($var, \Google\Cloud\RecommendationEngine\V1beta1\CatalogItem::class);
        $this->catalog_item = $var;

        return $this;
    }

    /**
     * Optional. Indicates which fields in the provided 'item' to update. If not
     * set, will by default update all fields.
     *
     * Generated from protobuf field <code>.google.protobuf.FieldMask update_mask = 3 [(.google.api.field_behavior) = OPTIONAL];</code>
     * @return \Google\Protobuf\FieldMask
     */
    public function getUpdateMask()
    {
        return $this->update_mask;
    }

    /**
     * Optional. Indicates which fields in the provided 'item' to update. If not
     * set, will by default update all fields.
     *
     * Generated from protobuf field <code>.google.protobuf.FieldMask update_mask = 3 [(.google.api.field_behavior) = OPTIONAL];</code>
     * @param \Google\Protobuf\FieldMask $var
     * @return $this
     */
    public function setUpdateMask($var)
    {
        GPBUtil::checkMessage($var, \Google\Protobuf\FieldMask::class);
        $this->update_mask = $var;

        return $this;
    }

}

