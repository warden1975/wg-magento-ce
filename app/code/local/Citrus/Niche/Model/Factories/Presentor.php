<?php

/**
 * Class Citrus_Niche_Model_Factories_Presentor
 */
class Citrus_Niche_Model_Factories_Presentor extends Citrus_Export_Model_Factories_Presentor
{

    /**
     * @return Citrus_Services_Presentor|mixed
     */
    public function build()
    {

        $this->presentor->addChild(
            new Citrus_Export_Model_Services_Extractors_Core($this->models->order, $this->models->billing)
        );

        $this->presentor->addChild(
            new Citrus_Export_Model_Services_Extractors_Addresses($this->models->billing, $this->models->shipping, $this->services->getCountry())
        );

        $this->presentor->addChild(
            new Citrus_Export_Model_Services_Extractors_Shipping($this->services->getAustPost())
        );

        $this->presentor->addChild(
            new Citrus_Export_Model_Services_Extractors_Items($this->models->order, $this->models->order->getAllVisibleItems(), $this->services->getProduct(), $this->models->billing)
        );

        $this->presentor->addChild(
            new Citrus_Export_Model_Services_Extractors_Payments($this->models->order->getPaymentsCollection(), $this->models->billing)
        );

        return $this->presentor;
    }

}