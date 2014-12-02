<?php

class exampleChannelAdvisorAuth
{
    public $DeveloperKey;
    public $Password;

    public function __construct($key, $pass)
    {
        $this->DeveloperKey = $key;
        $this->Password = $pass;
    }
}

/**
 * Class Citrus_Niche_Model_Observer
 */
class Citrus_Niche_Model_Observer extends Citrus_Export_Model_Observer
{
    public $DeveloperKey;
    public $Password;

    /**
     * @param $key
     * @param $pass
     */
    public function __construct($key, $pass)
    {
        $this->DeveloperKey = $key;
        $this->Password = $pass;
    }

    /**
     * @param $event
     * @return bool
     */
    public function exportOrder($event)
    {
        ob_start();
        try {

            list(
                /** @var Citrus_Niche_Model_Factories_Subject $Citrus_Niche_Model_Factories_Subject */
                $Citrus_Niche_Model_Factories_Subject,

                /** @var \api\data\adapters\pdo\client */
                $api_data_adapters_wsdl_client

            ) = $this->bootstrap($event);

            /** @var Citrus_Export_Model_Patterns_Subject $Citrus_Niche_Model_Patterns_Subject */
            $Citrus_Niche_Model_Patterns_Subject = $Citrus_Niche_Model_Factories_Subject->build( $api_data_adapters_wsdl_client , $event->getInvoice()->getOrder() );

            $Citrus_Niche_Model_Patterns_Subject->update();

            return true;

        } catch(\Exception $e) {
            Mage::logException($e);
            echo "File: " , $e->getFile(), " - Line: ", $e->getLine(), " . ", $e->getMessage(), "\n";
        }
        ob_get_clean();
    }

    /**
     * @param $event
     * @return array
     */
    public function bootstrap($event)
    {
        $namespace = $this->getNamespace();

        $Citrus_Export_Model_Factories_WSDL = "{$namespace}_Model_Factories_WSDL";
        $Citrus_Export_Model_Factories_Subject = "{$namespace}_Model_Factories_Subject";

        $autoload = new Citrus_Services_Autoload;
        $autoload->autoload();

        $loader = new \api\data\adapters\parseini\loader(MAGENTO_ROOT);

        $wsdl_factory = new $Citrus_Export_Model_Factories_WSDL($loader);

        return array(new $Citrus_Export_Model_Factories_Subject, $wsdl_factory->build());
    }
}