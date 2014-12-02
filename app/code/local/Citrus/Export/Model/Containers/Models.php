<?php
/**
 */

class Citrus_Export_Model_Containers_Models {

    /**
     * @var Mage_Sales_Model_Order_Invoice
     */
    public $invoice;

    /**
     * @var Mage_Sales_Model_Order
     */
    public $order;

    /**
     * @var array of Mage_Sales_Model_Order_Item | MDN_AdvancedStock_Model_Sales_Order_Item
     */
    public $items;

    /**
     * @var Mage_Sales_Model_Order_Address
     */
    public $billing;

    /**
     * @var Mage_Sales_Model_Order_Address
     */
    public $shipping;

    public function __construct( $invoice, $order, $items, $billing, $shipping){
        $this->invoice = $invoice;
        $this->order = $order;
        $this->items = $items;
        $this->billing = $billing;
        $this->shipping = $shipping;
    }
}