<?php

abstract class Citrus_Export_Model_Interfaces_Presentor {

    public function __construct($core, $customer, $addresses, $delivery, $items, $shipping, $payments){

        $this->core = $core;
        $this->customer = $customer;
        $this->addresses = $addresses;
        $this->delivery = $delivery;
        $this->items = $items;
        $this->shipping = $shipping;
        $this->payments = $payments;

    }

    abstract public function present();

}
