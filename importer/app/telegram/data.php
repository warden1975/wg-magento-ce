<?php

namespace app\telegram;

use interfaces;

class data implements interfaces\data {

    /**
     * @var api\data\adapters\pdo\client $getdata
     */
    protected $getdata;

    /**
     * @param api\data\adapters\pdo\client $getdata
     */
    public function __construct($getdata){
        $this->getdata = $getdata;

    }

    /**
     * Gets rows from the telegram database using the telegram/collections/telegram_products.ini query
     * @param $vars
     * @return array
     */
    public function getRows($vars){
        $database = $this->getdata->selectDB($vars['database']);
        $product = $database->selectCollection($vars['collection']);
        $rows = $product->find($vars['params']);
        return $rows;
    }

}