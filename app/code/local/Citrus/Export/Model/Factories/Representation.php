<?php
/**
 */


interface presentor {
    public function addChild(Citrus_Builder $child);
    public function present();
}

interface core {
    public function getData();
}

class builders
{
    public function __construct(
        Citrus_Model_Export_Factories_Core $core,
        Citrus_Model_Export_Factories_Customer $customer,
        Citrus_Model_Export_Factories_Addresses $addresses,
        Citrus_Model_Export_Factories_Delivery $delivery,
        Citrus_Model_Export_Factories_Items $items,
        Citrus_Model_Export_Factories_Shipping $shipping,
        Citrus_Model_Export_Factories_Payments $payments
    )
    {
        $this->Citrus_Export_Model_Objects_Core = $core;
        $this->Citrus_Export_Model_Objects_Customer = $customer;
        $this->Citrus_Export_Model_Objects_Addresses = $addresses;
        $this->Citrus_Export_Model_Objects_Delivery = $delivery;
        $this->Citrus_Export_Model_Objects_Items = $items;
        $this->Citrus_Export_Model_Objects_Shipping = $shipping;
        $this->Citrus_Export_Model_Objects_Payments = $payments;
    }
}



