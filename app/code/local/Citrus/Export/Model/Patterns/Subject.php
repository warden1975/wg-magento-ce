<?php

/**
 * Class Citrus_Export_Model_Patterns_Subject
 */
class Citrus_Export_Model_Patterns_Subject extends Citrus_Patterns_Subject
{

    /**
     * @var SplObjectStorage
     */
    protected $SplObjectStorage;

    /**
     * @var Citrus_Services_Presentor
     */
    protected $Citrus_Services_Presentor;

    /**
     * @var api\data\adapters\pdo\client
     */
    protected $PDO_Ini;

    /**
     * @param $SplObjectStorage
     * @param $Citrus_Services_Presentor
     * @param $PDO_Ini
     */
    public function __construct($SplObjectStorage, $Citrus_Services_Presentor, $PDO_Ini)
    {

        $this->SplObjectStorage = $SplObjectStorage;
        $this->Citrus_Services_Presentor = $Citrus_Services_Presentor;
        $this->PDO_Ini = $PDO_Ini;

    }

    /**
     * @return mixed
     */
    public function getPresentor()
    {
        return $this->Citrus_Services_Presentor->build();
    }

    /**
     * @return \api\data\adapters\pdo\client
     */
    public function getPdoClient()
    {
        return $this->PDO_Ini;
    }

}