<?php

/**
 * Class Citrus_Niche_Model_Factories_Subject
 */
class Citrus_Niche_Model_Factories_Subject
{
    /**
     * @param SoapClient $wsdl_client
     * @param $model
     * @return Citrus_Niche_Model_Patterns_Subject
     */
    public function build(SoapClient $wsdl_client, $model )
    {
        $subject = new Citrus_Niche_Model_Patterns_Subject(new SplObjectStorage, $model);

        $subject->attach(
            new Citrus_Niche_Model_Services_Order( $wsdl_client )
        );

        return $subject;
    }

}