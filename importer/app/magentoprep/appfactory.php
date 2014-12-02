<?php
/**
 * User: hone
 * Date: 27/03/13
 * Time: 11:17 AM
 */

namespace app\magentoprep;
use interfaces, app;

class appfactory implements interfaces\appfactory {

    /**
     * @var string
     */
    protected $path;

    /**
     * @param $path
     */
    public function __construct($path){
        $this->path = $path;
    }
    /**
     * @return app\magentoprep
     */
    public function build(){

        $app = new app\magentoprep(new \SplObjectStorage());
        $app->attach(
            new configurable (
                new \Csv_Reader(
                    $this->path ."../var/import/mark_shopify.csv",
                    new \Csv_Dialect(
                        array('quotechar' => '"', 'quoting' => \Csv_Dialect::QUOTE_MINIMAL)
                    )
                ),
                new \Csv_Reader(
                    $this->path ."../var/import/latest_stock_hone_shopify.csv",
                    new \Csv_Dialect(
                        array('quotechar' => "'", 'escapechar' => "'", 'quoting' => \Csv_Dialect::QUOTE_ALL)
                    )
                ),
                new \Csv_Writer(
                    $this->path ."../var/import/mark_shopify3.csv",
                    new \Csv_Dialect(
                        array('quotechar' => "'", 'escapechar' => "'", 'quoting' => \Csv_Dialect::QUOTE_ALL)
                    )
                ),
                $this->path
            )
        );
        //echo $this->path . 'config/shopify_categories_detailed.ini';
        /*
        $app->attach(
            new categories(
                \Mage::getModel('catalog/category'),
                parse_ini_file($this->path . 'config/shopify_categories_detailed.ini', true),
                new lib\iniwriter($this->path . 'config/magento_categories_map.ini')
            )
        );

        $app->attach(
            new categoriesmap(
                parse_ini_file($this->path . 'config/shopify_categories.ini', true),
                parse_ini_file($this->path . 'config/magento_categories_map.ini', true),
                new lib\iniwriter($this->path . 'config/magento_categories.ini')
            )
        );

        */
        return $app;
    }
}