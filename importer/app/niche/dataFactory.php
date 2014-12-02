<?php

namespace app\niche;

use interfaces;

/**
 * Class dataFactory
 * @package app\niche
 */
class dataFactory implements interfaces\appfactory
{

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
     * @return array
     * @throws \Exception
     */
    public function build()
    {
        return new data(
             new \api\data\adapters\xml\loader($this->params['url_products']),
             new \api\data\adapters\xml\loader($this->params['url_styles'])
        );
    }

}