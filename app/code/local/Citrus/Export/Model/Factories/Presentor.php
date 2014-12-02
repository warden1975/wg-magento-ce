<?php
/**

 */

abstract class Citrus_Export_Model_Factories_Presentor implements Citrus_Interfaces_Builder
{
    /**
     * @var Citrus_Services_Presentor
     */
    protected $presentor;

    /**
     * @var Citrus_Export_Model_Containers_Services
     */
    protected $services;

    /**
     * @var Citrus_Export_Model_Containers_Models
     */
    protected $models;

    /**
     * @param $presentor
     * @param $services
     * @param $models
     */
    public function __construct( $presentor, $services, $models )
    {
        $this->presentor = $presentor;
        $this->services = $services;
        $this->models = $models;
    }

    /**
     * @return mixed
     */
    abstract public function build();

}
