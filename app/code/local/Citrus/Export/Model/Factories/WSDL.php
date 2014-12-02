<?php

/**
 * Class Citrus_Export_Model_Factories_WSDL
 */
abstract class Citrus_Export_Model_Factories_WSDL
{

    /**
     * @var \api\data\adapters\parseini\loader
     */
    protected $loader;

    /**
     * @param $loader
     */
    public function __construct($loader)
    {
        $this->loader = $loader;
    }

    /**
     * @param array $soap_client_params
     * @return SoapClient
     * @throws Exception
     */
    abstract function build(array $soap_client_params = array());
}