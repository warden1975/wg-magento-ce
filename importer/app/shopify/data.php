<?php

namespace app\shopify;

use lib;

class data {

    /**
     * @var sandeepshetty\shopify_api\client $getdata
     */
    protected $getdata;

    /**
     * @param sandeepshetty\shopify_api\client $getdata
     */
    public function __construct($getdata){
        $this->getdata = $getdata;

    }

    /** Example array('GET', '/admin/collects.json', array('product_id' => $id))
     * @param array $vars array($type, $path, $filters= array()) <br /> $type is the request, $path is the request path and $filters are api filters
     * @return mixed
     */
    public function getRows($vars){
        if(!sizeof($vars) >= 2 )
            throw Exception('API params incorrect for sandeepshetty\shopify_api\client closure params');
        $filters = array();
        if(isset($vars[2]))
            $filters = $vars[2];
        $data = $this->getdata;
        //$rows =  $data($type, $path, $filters);
        $rows =  $data($vars[0], $vars[1], $filters);
        return $rows;
    }

}