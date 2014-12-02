<?php

/**
 */
class Citrus_Export_Model_Factories_PDO
{

    /**
     * @var \api\data\adapters\parseini\loader
     */
    protected $loader;

    public function __construct($loader){
        $this->loader = $loader;
    }

    /**
     * @return \api\data\adapters\pdo\client
     */
    public function build()
    {
        //$params = $this->loader->load("/../../../../../../../importer/config/telegram.ini");
        $params = $this->loader->load("config.ini");

        $data_params = $params['app']['data_params'];

        $factory = new Citrus_Factories_PDO(
            $this->loader,
            '\api\data\adapters\pdo\client'
        );
        $client = $factory->build($data_params);
        $db = $client->selectDB($data_params['db']);
        return $db;
    }
}