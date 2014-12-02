<?php
/**
 */

class Citrus_Export_Model_Observer
{

    /**
     * @param $event
     */
    public function createMockForUseInShellExport($event)
    {
        try {

            if(!file_exists(MAGENTO_ROOT . "/var/mocks/")) {
                mkdir(MAGENTO_ROOT . "/var/mocks/", 0755);
            }

        } catch(Exception $e) { }

        try {

            file_put_contents(MAGENTO_ROOT . "/var/mocks/invoice", serialize($event->getInvoice()->toArray()));

        } catch(Exception $e) {
            echo "Could not create mock! " . $e->getMessage();
        }

    }

    /**
     * @param $event
     * @return array
     */
    public function bootstrap($event)
    {

        $namespace = $this->getNamespace();

        $Citrus_Export_Model_Factories_Container_Services = "{$namespace}_Model_Factories_Containers_Services";

        $Citrus_Export_Model_Factories_Container_Models = "{$namespace}_Model_Factories_Containers_Models";

        $Citrus_Export_Model_Factories_PDO = "{$namespace}_Model_Factories_PDO";

        $Citrus_Export_Model_Factories_Presentor = "{$namespace}_Model_Factories_Presentor";

        $Citrus_Export_Model_Formatter = "{$namespace}_Model_Formatter";

        $Citrus_Export_Model_Factories_Subject = "{$namespace}_Model_Factories_Subject";

        $autoload = new Citrus_Services_Autoload;

        $autoload->autoload();

        $loader = new \api\data\adapters\parseini\loader(MAGENTO_ROOT . str_replace("_", "/", "/app/code/local/$namespace/Model/Ini"));

        $models_factory = new $Citrus_Export_Model_Factories_Container_Models($event->getInvoice());

        $model_container = $models_factory->build($namespace);

        $services_factory = new $Citrus_Export_Model_Factories_Container_Services;

        $dbfactory = new $Citrus_Export_Model_Factories_PDO($loader);

        $presentor_factory = new $Citrus_Export_Model_Factories_Presentor(
            new Citrus_Services_Presentor(
                new $Citrus_Export_Model_Formatter()
            ),
            $services_factory->build($model_container, $namespace),
            $model_container
        );

        return array(new $Citrus_Export_Model_Factories_Subject, $presentor_factory->build(), $dbfactory->build());

    }

    /**
     * @return string
     */
    public function getNamespace()
    {

        $namespace = explode("_", get_class($this));

        return "{$namespace[0]}_{$namespace[1]}";

    }
}