<?php
/**
 */

class Citrus_Export_Model_Services_Extractors_Core implements Citrus_Interfaces_Builder{

    /**
     * @var  Mage_Sales_Model_Order
     */
    protected $order;

    /**
     * @var Mage_Sales_Model_Order_Address
     */
    protected $billing;

    /**
     * @param $order Mage_Sales_Model_Order
     * @param $billing Mage_Sales_Model_Order_Address
     */
    public function __construct($order, $billing){
        $this->order = $order;
        $this->billing = $billing;
    }

    /**
     * @return object
     */
    public function build(){
        return (object) array(
            'order_increment_id'    => $this->order->getIncrementId(),
            'email'	                => $this->order->getCustomerEmail(),
            'home'	                => $this->billing->getTelephone(),
            'subtotal'              => $this->order->getSubtotal(),
            'grand_total'                 => $this->order->getGrandTotal(),
        );
    }
}