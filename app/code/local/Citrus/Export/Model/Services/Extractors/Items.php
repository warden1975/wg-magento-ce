<?php
/**
 */

class Citrus_Export_Model_Services_Extractors_Items implements Citrus_Interfaces_Builder {

    /**
     * @var Mage_Sales_Model_Order
     */
    protected $order;

    /**
     * @var array
     */
    protected $items;

    /**
     * @var Mage_Catalog_Model_Product
     */
    protected $product;

    /**
     * @var Mage_Sales_Model_Order_Address
     */
    protected $billing;

    /**
     * @param $order Mage_Sales_Model_Order
     * @param $items array
     * @param $product Mage_Catalog_Model_Product
     * @param $billing Mage_Sales_Model_Order_Address
     */
    public function __construct($order, $items, $product, $billing){

        $this->order = $order;
        $this->items = $items;
        $this->product = $product;
        $this->billing = $billing;
    }

    /**
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct(){
        return clone $this->product;
    }

        /**
     * @return bool
     */
    public function isInternational(){
 		if( !in_array($this->billing->getCountryId(), array('NZ', 'AU')) )
			return true;
		else return false;
    }

    /**
	 * @return bool
	 */
	public function isNZ(){
		if($this->billing->getCountryId() == 'NZ')
			return true;
		else return false;
	}

    /**
     * @return array
     */
    public function build(){

		$discount_description = $this->order->getDiscountDescription();

        // Mage_Sales_Model_Order_Item | MDN_AdvancedStock_Model_Sales_Order_Item
		/* @var $item Mage_Sales_Model_Order_Item */
		foreach($this->items as $item) {

			$product = $this->getProduct()->load(
			    $this->getProduct()->getIdBySku($item->getSku())
			);

			if($this->isNZ() || $this->isInternational()) {
				$price = $item->getBasePrice();
				$discount_amount = $item->getBaseDiscountAmount();
			}
			else {
				$price = $item->getPriceInclTax();
				$discount_amount = round($item->getDiscountAmount(), 2);
			}

            $item = (object)array(
                "filemaker_id" => $product->getFilemakerId(),
                "quantity" => $item->getQtyOrdered(),
                "price" => round($price, 2),
                "discount_amount" => $discount_amount,
                "discount_description" => $discount_description,
                "discount_percentage" =>round(($discount_amount/$price) * 100, 2),
                'full_product' => $product->toArray(),
            );

            $final_items[] = $item;

        }

        return $final_items;
    }

}