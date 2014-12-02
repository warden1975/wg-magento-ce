<?php
/**
 */

class Citrus_Factories_PDO_Queryadapter implements Citrus_Interfaces_Buildervar{

    /**
     * @var Citrus_Factories_PDO
     */
    protected $Citrus_Factories_PDO;

    /**
     * @param $Citrus_Factories_PDO Citrus_Factories_PDO
     */
    public function __construct($Citrus_Factories_PDO){
        $this->Citrus_Factories_PDO = $Citrus_Factories_PDO;
    }

    /**
     * @param $database
     * @param string $pdoadapter '\api\data\adapters\pdo\query', '\api\data\adapters\pdo\get\collection'
     */
    public function build($database, $pdoadapter = '\api\data\adapters\pdo\query'){
        $db = $this->Citrus_Factories_PDO->build($pdoadapter);

    }

}