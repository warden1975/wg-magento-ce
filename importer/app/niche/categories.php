<?php
/**
 * User: hone
 * Date: 29/04/13
 * Time: 11:28 AM
 */

namespace app\niche;

use interfaces;

class categories implements interfaces\databyid
{
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
    public function __construct($data, $magento_categories)
    {
        $this->data = $data;
        $this->magento_categories = $magento_categories;
    }

    /**
     * @param $id
     * @return string
     */
    public function getDataById($id)
    {

        return '';
    }

    /**
     * @param $niche_dept
     * @param $niche_cat
     * @return string
     */
    public function getDataByName($niche_dept, $niche_cat)
    {
        $collect_ids = array();

        foreach($this->magento_categories['Categories'] as $categories_map => $collection_id) {

            $categories_map = explode('.', $categories_map);
            $map_dept = null;
            $map_cat = null;
            if(isset($categories_map[0])){
                $map_dept = $categories_map[0];
            }
            if(isset($categories_map[1])){
                $map_cat = $categories_map[1];
            }

            if($niche_dept == $map_dept && $niche_cat == $map_cat) {
                $collect_ids[] = $collection_id;
            }
        }
        
        return implode(',', $collect_ids);
    }
}