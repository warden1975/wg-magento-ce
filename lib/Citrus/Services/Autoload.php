<?php
/**
 */

class Citrus_Services_Autoload
{

    public function autoload()
    {
        try {

            require_once MAGENTO_ROOT . "/importer/autoload.php";
            $autoload = new autoload( array(MAGENTO_ROOT . "/importer/vendor/", MAGENTO_ROOT . "/importer/"), new Errors());
            $autoload->autoload();

            return true;

        } catch(Exception $e) {
            throw new Exception($e->getMessage());
        }

    }

}