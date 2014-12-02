<?php

namespace app\shopify;
use sandeepshetty, lib;

class appfactory {

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
     * @return mixed
     */
    public function build(){

        $mainconfig = $this->config['shopify'];
        $data = new data (
                sandeepshetty\shopify_api\client(
                    $mainconfig['domain'],
                    NULL,
                    $mainconfig['apiKey'],
                    $mainconfig['sharedSecret'],
                    true
                )
            );

        return new $mainconfig['class'](

            $data,
            new mapper(
                parse_ini_file( $this->path . $mainconfig['mapperFile'], true ),
                new $mainconfig['callbacks'] (
                    new categories(
                        $data,
                        parse_ini_file( $this->path . 'config/magento_categories.ini', true )
                    ),
                    new attributes(
                        parse_ini_file( $this->path . 'config/attributes.ini', true )
                    ),
                    new fromhtml(
                        new domfactory
                    )
                )
            ),
            new configurable,
            new \Csv_Writer(
                $this->path .".." . $mainconfig['generatedCsvName'],
                new \Csv_Dialect(
                    array('quotechar' => "'", 'escapechar' => "'", 'quoting' => \Csv_Dialect::QUOTE_ALL)
                )
            )

        );
    }
}