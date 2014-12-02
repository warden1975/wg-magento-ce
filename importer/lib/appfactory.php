<?php

namespace lib;
use interfaces;

class appfactory implements interfaces\appfactory
{

    /**
     * @var array
     */
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

        $mainconfig = $this->config['app'];
        $data = new $mainconfig['data_factory']($mainconfig['data_params'], $this->path);
        $data = $data->build();
        $configurable = new $mainconfig['configurableFactory'];

        return new $mainconfig['class'](

            $data,
            new $mainconfig['mapper'](
                parse_ini_file( $this->path . $mainconfig['mapperFile'], true ),
                new $mainconfig['callbacks'] (
                    new $mainconfig['categories'](
                        $data,
                        parse_ini_file( $this->path . $mainconfig['magentoCategoryMap'], true )
                    ),
                    new $mainconfig['attributes'](
                        parse_ini_file( $this->path . 'config/attributes.ini', true )
                    ),
                    new $mainconfig['fromhtml'](
                        new domfactory
                    )
                )
            ),
            $configurable->build(),
            new \Csv_Writer(
                $this->path .".." . $mainconfig['generatedCsvName'],
                new \Csv_Dialect(
                    array('quotechar' => "'", 'escapechar' => "'", 'quoting' => \Csv_Dialect::QUOTE_ALL)
                )
            ),
            $mainconfig

        );
    }
}