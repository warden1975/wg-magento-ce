<?php
/**
 */

class Citrus_Export_Model_Factories_Containers_Models {

    /**
     * @var Mage_Sales_Model_Order_Invoice
     */
    protected $invoice;

    /**
     * @param $invoice Mage_Sales_Model_Order_Invoice
     */
    public function __construct($invoice) {
        $this->invoice = $invoice;
    }

    /**
     * @return Citrus_Export_Model_Containers_Models
     */
    public function build($namespace){


        $class = "{$namespace}_Model_Containers_Models";

		$invoice = $this->invoice;

		$order = $invoice->getOrder();

        $items = $order->getAllVisibleItems();

        $billing = $order->getBillingAddress();

        $shipping = $order->getShippingAddress();

		if(!is_object( $shipping ))
			$shipping  = $billing;


        $instance = new $class(
            $invoice,
            $order,
            $items,
            $billing,
            $shipping
        );

        return $instance;
    }
}