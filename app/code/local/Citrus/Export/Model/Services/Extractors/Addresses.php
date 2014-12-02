<?php
/**
 */

class Citrus_Export_Model_Services_Extractors_Addresses implements Citrus_Interfaces_Builder
{

    /**
     * @var Mage_Sales_Model_Order_Address
     */
    protected $billing;

    /**
     * @var Mage_Sales_Model_Order_Address
     */
    protected $shipping;

    /**
     * @var Mage_Directory_Model_Country
     */
    protected $country;

    /**
     * @param $billing Mage_Sales_Model_Order_Address
     * @param $shipping Mage_Sales_Model_Order_Address
     * @param $country Mage_Directory_Model_Country
     */
    public function __construct($billing, $shipping, $country)
    {
        $this->billing = $billing;
        $this->shipping = $shipping;
        $this->country = $country;
    }

    /**
     * @param $address Mage_Sales_Model_Order_Address
     * @return array
     */
    public function getAddress($address)
    {

        $country = $this->country->load($address->getCountryId());

        return array(
            'fullname' => $address->getFirstname() . " " . $address->getLastname(),
            'address_line_1' => $address->getStreet1(),
            'address_line_2' => $address->getStreet2(),
            'city' => $address->getCity(),
            'state' => $address->getRegion(),
            'postcode' => $address->getPostcode(),
            'country' => $country->getName()
        );

    }

    /**
     * @return object
     */
    public function build()
    {

        return (object)array(
            'delivery' => $this->getAddress($this->shipping),
            'billing' => $this->getAddress($this->billing),
        );

    }

}