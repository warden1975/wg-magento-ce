<?php
/**
 * User: hone
 * Date: 26/03/13
 * Time: 10:31 AM
 */

namespace app\shopify;


class categories {

    /**
     * @var data
     */
    public $data;

    /**
     * @var array
     */
    public $magento_categories;

    /**
     * @param app\shopify\data $data
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
    public function getData($id){
        $collection = $this->data->getRows( array('GET', '/admin/collects.json', array('product_id' => $id)) );


        foreach($collection as $collect) {
            if(isset($this->magento_categories['Categories'][$collect['collection_id']]))
                $collect_ids[] = $this->magento_categories['Categories'][$collect['collection_id']];
        }

        return implode(',', $collect_ids);
    }
}