<?php
/**
 * User: hone
 * Date: 27/03/13
 * Time: 11:26 AM
 */

namespace app\magentoprep;

use interfaces, lib;

class categories implements interfaces\app {
    /**
     * @var Mage_Category_Model_Category
     */
    public $Mage_Category_Model_Category;

    /**
     * @var array
     */
    public $categorydata;

    /**
     * @var lib\iniwriter
     */
    protected $iniwriter;
    /**
     * @param Mage_Category_Model_Category $Mage_Category_Model_Category
     * @param array $categorydata
     * @param iniwriter $iniwriter
     */
    public function __construct($Mage_Category_Model_Category, $categorydata, $iniwriter){
        $this->Mage_Category_Model_Category = $Mage_Category_Model_Category;
        $this->categorydata = $categorydata;
        $this->iniwriter = $iniwriter;
    }

    /**
     * @return Mage_Category_Model_Category
     */
    public function newCategory(){
        return clone $this->Mage_Category_Model_Category;
    }

    /**
     * @return string
     */
    public function run(){


        foreach($this->categorydata as $key => $values){

            $cat = $this->newCategory();
            $values['display_mode'] = "PRODUCTS_AND_PAGE";
            $values['is_active'] = 1;
            $values['is_anchor'] = 0;
            $values['parent_id'] = 3;
            $values['attribute_set_id'] = 3;
            $values['path'] = "1/2/3";
            $values['page_layout'] = 'two_columns_left';

            $cat->addData($values);

            try {
                $cat->save();
                $magento_cats[$values['name']] = $cat->getId();
                echo "\nSuccess! Id: ".$cat->getId();
            }
            catch (Exception $e){
                echo "\n" . $e->getMessage();
            }
        }

        $this->iniwriter->write("Categories", $magento_cats);
        return "Done " . __CLASS__;

    }
}