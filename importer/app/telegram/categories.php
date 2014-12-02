<?php
/**
 * User: hone
 * Date: 29/04/13
 * Time: 11:28 AM
 */

namespace app\telegram;

use interfaces;

class categories implements interfaces\databyid {
    /**
     * @var data
     */
    public $data;

    /**
     * @var array
     */
    public $magento_categories;

    /**
     * @param data $data
     * @param array $magento_categories
     */
    public function __construct($data, $magento_categories){
        $this->data = $data;
        $this->magento_categories = $magento_categories;
    }

    /**
     * @param $id
     * @return string
     */
    public function getDataById($id){

        //TODO set up $vars
        //$collection = $this->data->getRows( $vars );


        foreach($collection as $collect) {
            if(isset($this->magento_categories['Categories'][$collect['collection_id']]))
                $collect_ids[] = $this->magento_categories['Categories'][$collect['collection_id']];
        }

        return implode(',', $collect_ids);
    }

    /**
     * @param $name
     * @return string
     */
    public function getDataByName($name){

        foreach($this->magento_categories['Categories'] as $cat_name => $collection_id) {
            if($name == $cat_name)
                $collect_ids[] = $collection_id;
        }

        if(!isset($collect_ids))
            $collect_ids[] = 3;

        return implode(',', $collect_ids);
    }
}