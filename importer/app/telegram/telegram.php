<?php

namespace app\telegram;

use interfaces;

class telegram implements interfaces\app
{

    /**
     * @var array
     */
    protected $data;


    /**
     * @var mapper
     */
    protected $mapper;

    /**
     * @var configurable
     */
    protected $configurable;

    /**
     * @var Csv_Writer
     */
    protected $Csv_Writer;

    /**
     * @var array
     */
    protected $mainconfig;

    /**
     * @param $data data
     * @param $mapper mapper
     * @param $configurable configurable
     * @param $Csv_Writer \Csv_Writer
     * @param $mainconfig array
     */
    public function __construct($data, $mapper, $configurable, $Csv_Writer, $mainconfig)
    {
        $this->data = $data;
        $this->mapper = $mapper;
        $this->configurable = $configurable;
        $this->Csv_Writer = $Csv_Writer;
        $this->mainconfig  = $mainconfig;

    }

    /**
     *  Gets telegram data, gets simple products from data using mapper,
     *  Creates configurable products
     *  Writes row to csv
     *  config/telegram.ini
     *  config/telegram_mapper.ini
     *  @return string
     */
    public function run()
    {
        $rows = array();

        $data['database'] = $this->mainconfig['telegramdb'];

        $data['collection'] = "telegram_products.ini";

        $data['params']['query'] = 'products';


        foreach( $this->data->getRows($data) as $row) {
                $rows[] = $row;
        }

        $headers = $this->mapper->getHeaders();

        $this->Csv_Writer->writeRow($headers);
        $new_rows = array();

        foreach($rows as $row) {
            $new_rows[] = $this->mapper->getExtractedValue((array)$row);
        }


        //TODO you need to create the configurable class that converts a list of simple products into the simple/configurable format
        $this->configurable->setHeaders($headers);

        foreach($this->configurable->getConfigurables( $new_rows ) as $row) {
            $this->Csv_Writer->writeRow($row);
        }

        return "\n\nDone!\n\n";
    }

}