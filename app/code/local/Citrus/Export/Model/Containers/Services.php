<?php
/**
 */

class Citrus_Export_Model_Containers_Services {

    /**
     * @var Citrus_Export_Model_Services_Shipping_Australiapost
     */
    protected $auspost;

    /**
     * @var Mage_Directory_Model_Country
     */
    protected $country;

    /**
     * @var Mage_Catalog_Model_Product
     */
    protected $product;

    /**
     * @param $auspost
     * @param $country
     * @param $product
     */

    public function __construct( $auspost, $country, $product){
        $this->auspost = $auspost;
        $this->country = $country;
        $this->product = $product;
    }

    /**
     * @return Citrus_Export_Model_Services_Shipping_Australiapost
     */
    public function getAustPost(){
        return clone $this->auspost;
    }

    /**
     * @return Mage_Directory_Model_Country
     */
    public function getCountry(){
        return clone $this->country;
    }

    /**
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct(){
        return clone $this->product;
    }

}