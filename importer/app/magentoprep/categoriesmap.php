<?php
/**
 * User: hone
 * Date: 27/03/13
 * Time: 3:44 PM
 */

namespace app\magentoprep;

use interfaces, lib;

class categoriesmap implements interfaces\app {

    /**
     * @var array
     */
    public $shopify_categories;

    /**
     * @var array
     */
    public $magento_categories;

    /***
     * @var lib\iniwriter
     */
    public $iniwriter;

    /**
     * @param $shopify_categories
     * @param $magento_categories
     * @param $iniwriter
     */
    public function __construct($shopify_categories, $magento_categories, $iniwriter){
        $this->shopify_categories = $shopify_categories;
        $this->magento_categories = $magento_categories;
        $this->iniwriter = $iniwriter;
    }

    /**
     *
     */
    public function run(){
        //#####
        //++++++
        foreach($this->magento_categories['Categories'] as $key => $value)
            $magento_categories[str_replace(array('#####', '++++++', '^^^'), array('&', '$', '!'), $key)] = $value;
        foreach($this->shopify_categories['Categories'] as $key => $value){
            if(isset( $magento_categories[$value]))
                $cats[$key] = $magento_categories[$value];

        }
        $this->iniwriter->write("Categories", $cats);
        echo sprintf("\n\nDone running %s...\n\n", __CLASS__);
    }
}