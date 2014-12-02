<?php

/**
 */
try {
    /**
     * @var $installer Mage_Core_Model_Resource_Setup
     */
    $installer = $this;
    $installer->startSetup();

    $installer->endSetup();


} catch (Excpetion $e) {
    Mage::logException($e);
    Mage::log("ERROR IN SETUP " . $e->getMessage());
}

