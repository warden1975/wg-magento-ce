<?php

namespace app;
use interfaces;

class shopify implements interfaces\app {


    /**
     * @var array
     */
    protected $data;

    /**
     * @var array
     */
    protected $collects;

    /**
     * @var mapper
     */
    protected $mapper;

    /**
     * @var Csv_Writer
     */
    protected $Csv_Writer;

    /**
     * @param $data app\shopify\data
     * @param $mapper
     * @param $Csv_Writer
     */
    public function __construct($data, $mapper, $configurable, $Csv_Writer)
    {
        $this->data = $data;
        $this->mapper = $mapper;
        $this->configurable = $configurable;
        $this->Csv_Writer = $Csv_Writer;

    }

    /**
     *  @return string
     */
    public function run()
    {
        $rows = array();

        $pages = range(1, 96);

        foreach($pages as $page)
            foreach( $this->data->getRows(array('GET', "/admin/products.json?page=$page", array('published_status'=>'published'))) as $row)
                $rows[] = $row;


        $this->Csv_Writer->writeRow($this->mapper->getHeaders());
        $new_rows = array();
        foreach($rows as $row)
            $new_rows[] = $this->mapper->getExtractedValue($row);
        //TODO you need to create the configurable class that converts a list of simple products into the simple/configurable format
        foreach($this->configurable->getConfigurables( $new_rows ) as $row)
            $this->Csv_Writer->writeRow($row);

        return "\n\nDone!\n\n";
    }

}