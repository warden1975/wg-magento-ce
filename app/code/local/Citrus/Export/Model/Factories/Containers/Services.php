<?php
/**
 */

class Citrus_Export_Model_Factories_Containers_Services {

    public function build($data, $namespace){
        $class = "{$namespace}_Model_Containers_Services";
        return new $class(
            Mage::getModel('citrus_export/services_shipping_australiapost')->setOrder($data->order),
            Mage::getModel('directory/country'),
            Mage::getModel('catalog/product')
        );
    }

}