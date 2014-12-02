<?php

/**
 * Class Citrus_Niche_Model_Factories_WSDL
 */
class Citrus_Niche_Model_Factories_WSDL extends Citrus_Export_Model_Factories_WSDL
{
    /**
     * @param array $soap_client_params
     * @return SoapClient
     * @throws Exception
     */
    public function build(array $soap_client_params = array())
    {
        // root/importer/config/niche.ini
        $params = $this->loader->load("importer/config/niche.ini");
        $api_params = $params['app']['api'];

        $soap_client = new \SoapClient( $api_params['wsdl_url'], $soap_client_params );
        $soap_result = $soap_client->LogIn(array(
            "userName" => $api_params['wsdl_user'],
            "password" => $api_params['wsdl_pass'],
        ));

        if($soap_result->LogInResult === false) {
            throw new \Exception('Could not login into Niche API. Access denied.');
        }

        return $soap_client;
    }
}