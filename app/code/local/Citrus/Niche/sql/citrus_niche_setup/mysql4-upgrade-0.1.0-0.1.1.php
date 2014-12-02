<?php

/**
 */
try {
    /**
     * @var $installer Mage_Core_Model_Resource_Setup
     */
    $installer = $this;
    $installer->startSetup();

    $model = Mage::getModel('eav/entity_setup','core_setup');
    $productTypes = array(
        Mage_Catalog_Model_Product_Type::TYPE_SIMPLE,
        Mage_Catalog_Model_Product_Type::TYPE_BUNDLE,
        Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE,
        Mage_Catalog_Model_Product_Type::TYPE_VIRTUAL
    );
    $data = array(
        'type' => 'varchar',
        'group' => 'General',
        'input' => 'text',
        'label' => 'Namekey',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'is_required' => '0',
        'is_comparable' => '0',
        'is_searchable' => '0',
        'is_unique' => '0',
        'is_configurable' => '0',
        'use_defined' => '1',
        'apply_to'      => $productTypes,
    );

    $model->addAttribute('catalog_product', 'namekey', $data);


    $entityTypeId = $model->getEntityTypeId('catalog_product');

    $attribute_id = $model->getAttributeId($entityTypeId, 'namekey');

    $sets = $installer->getConnection()->fetchAll('select * from '.$installer->getTable('eav/attribute_set').' where entity_type_id=?', $entityTypeId);
    foreach ($sets as $set) {
        $model->addAttributeToSet($entityTypeId, $set['attribute_set_id'], 'Namekey', 'namekey', 1);
    }

    // get product attribute_id
    $entityTypeId   = $model->getEntityTypeId('catalog_product');

    // Add attribute
    $productTypes = array(
        Mage_Catalog_Model_Product_Type::TYPE_SIMPLE,
        Mage_Catalog_Model_Product_Type::TYPE_BUNDLE,
        Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE,
        Mage_Catalog_Model_Product_Type::TYPE_VIRTUAL
    );

    $data = array(
//        'attribute_set'     => '',
        'group' => 'Custom',
        'label' => 'Size',
        'type'  => 'varchar',
        'input' => 'text',
//        'class'             => '',
        'backend' => '',
//        'frontend'          => '',
//        'source'            => ''
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible'           => true,
        'required'          => false,
        'user_defined'      => true,
        'searchable'        => false,
        'filterable'        => false,
        'comparable'        => false,
        'visible_on_front'  => false,
        'visible_in_advanced_search' => false,
        'unique'            => false,
        'apply_to' => $productTypes,
    );

    $model->addAttribute($entityTypeId, 'size', $data);

    $installer->endSetup();

} catch (Excpetion $e) {
    Mage::logException($e);
    Mage::log("ERROR IN SETUP " . $e->getMessage());
}

