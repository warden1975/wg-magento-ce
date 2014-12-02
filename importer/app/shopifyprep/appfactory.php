<?php

namespace app\shopifyprep;
use sandeepshetty, app\shopify, app, interfaces, lib;

class appfactory implements interfaces\appfactory {

    protected $config;

    /**
     * @param $config
     * @param $path
     */
    public function __construct($config, $path){
        $this->config = $config;
        $this->path = $path;
    }

    /**
     * @return app\shopifyprep
     */
    public function build(){

        $mainconfig = $this->config['shopify'];
        $data = new \app\shopify\data (
                sandeepshetty\shopify_api\client(
                    $mainconfig['domain'],
                    NULL,
                    $mainconfig['apiKey'],
                    $mainconfig['sharedSecret'],
                    true
                )
            );

        $app = new app\shopifyprep(new \SplObjectStorage());

        $app->attach(
            new categories(
                $data,
                new lib\iniwriter($this->path . 'config/shopify_categories.ini'),
                new lib\iniwriter($this->path . 'config/shopify_categories_detailed.ini')
            )
        );

        return $app;
    }
}