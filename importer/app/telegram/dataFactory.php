<?php

namespace app\telegram;

use interfaces;

class dataFactory implements interfaces\appfactory {

    /**
     * An array from the config/*.ini document
     * @var array
     */
    protected $params;

    /**
     * @var string
     */
    protected $path;

    /**
     * @param $params
     * @param $path
     */
    public function __construct($params, $path){
        $this->params = $params;
        $this->path = $path;
    }

    /**
     * @return data
     */
    public function build(){
        return new data(
             new \api\data\adapters\pdo\client(
                new \PDO($this->params['pdo_one'], $this->params['pdo_two'], $this->params['pdo_three']),
                new \api\data\adapters\parseini\loader($this->path.$this->params['basepath']),
                '\api\data\adapters\pdo\get\collection'
            )
        );
    }
}