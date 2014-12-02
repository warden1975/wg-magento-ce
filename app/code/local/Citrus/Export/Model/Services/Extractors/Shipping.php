<?php
/**
 */

class Citrus_Export_Model_Services_Extractors_Shipping  implements Citrus_Interfaces_Builder {

    /**
     * @var Citrus_Export_Model_Services_Shipping_Australiapost
     */
    protected $shipping;

    /**
     * @param $shipping Citrus_Export_Model_Services_Shipping_Australiapost
     */
    public function __construct($shipping) {
        $this->shipping = $shipping;
    }

    /**
     * @return array
     */
    public function build(){
        return array(
            'selected_freight_option' => $this->shipping->toArray()
        );
    }

}