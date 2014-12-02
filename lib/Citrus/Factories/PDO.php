<?php
/**
 */

class Citrus_Factories_PDO implements Citrus_Interfaces_Buildervar{


    /**
     * @var api\data\adapters\parseini\loader
     */
    protected $loader;

    /**
     * @var string
     */
    protected $client;

    /**
     * @param $loader
     * @param string $client
     */
    public function __construct($loader, $client = '\api\data\adapters\pdo\client') {
        $this->loader = $loader;
        $this->client = $client;
    }

    /**
     * @param string $collection
     * @return \api\data\adapters\pdo\query
     */
    public function build($data_params){
        $dbget = new $this->client(
            new \PDO($data_params['pdo_one'], $data_params['pdo_two'], $data_params['pdo_three']),
            $this->loader,
            '\api\data\adapters\pdo\get\collection'
        );
        return $dbget;
    }

}