<?php
/**
 */

class Citrus_Export_Model_Services_Shipping_Australiapost {

    /**
     * Shipping codes
     * @var array
     */

    public $shipping_method_array = array(
        'australiapost_STANDARD' => 'Standard',
        'australiapost_EXPRESS' => 'Express',
        'australiapost_EPI' => 'International Flat Rate',
        'australiapost_EPINZ' => 'New Zealand Int',
        'australiapost_AIRNZ' => 'New Zealand Air',
        'australiapost_EPI-ECNZ' => 'New Zealand Int WCover',
        'freeshipping_freeshipping' => 'Free Standard'
    );

    /** @var Mage_Sales_Model_Order */
    public $orderObject;

	/**
	 * @param null $orderObject
	 */
	public function __construct($orderObject=null){
        $this->orderObject = $orderObject;
    }

	/**
	 * @param $orderObject
	 * @return Citrus_Ap21_Model_Shipping
	 */
	public function setOrder($orderObject){
        $this->orderObject = $orderObject;
        return $this;
    }

	/**
	 * @return float
	 */
	public function getBaseShippingAmount() {

        return $this->orderObject->getBaseShippingAmount()  + $this->orderObject->getBaseShippingTaxAmount();
    }

	/**
	 * @return string
	 */
	public function getShippingMethod(){
        $method = trim($this->orderObject->getShippingMethod());
        return $this->shipping_method_array[$method];
    }

	/**
	 * @return string
	 */
	public function getSelectedFreightOption(){
        $method = trim($this->orderObject->getShippingMethod());
        return $method;
    }

	/**
	 * @return array
	 */
	public function toArray(){

        return array(
            'freight_id' => $this->getSelectedFreightOption(),
            'shipping_method' => $this->getShippingMethod(),
            'base_shipping_amount' => $this->getBaseShippingAmount()
        );

    }

}