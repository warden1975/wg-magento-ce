<?php
/**
 * User: hone
 * Date: 26/03/13
 * Time: 4:55 PM
 */

namespace app\shopify;


class attributes {

    /**
     * @var array
     */
    public $config;

    protected $attributes_store = array();

    /**
     * @param $config
     */
    public function __construct($config){
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function getAttributes(){
        if($this->attributes_store === array()){
            foreach($this->config['attributes'] as $attribute => $values){
                $values = explode(",", strtolower($values));
                foreach($values as $value)
                    $this->attributes_store[$value] = $attribute;
            }

        }

        return $this->attributes_store;
    }

    /**
     * @param array $data
     * @param string $tagname
     * @return string
     */
    public function getAttributesFromTags($data, $tagname){

        if(!isset($data['tags']))
            return "";

        $tags = explode(",", str_replace(", ", ",", $data['tags']));

        $attributes = $this->getAttributes();

        foreach($tags as $tag){
            $ntag = trim($tag);
            if(isset($attributes[$ntag]) && $attributes[$ntag] == $tagname)
                return $tag;
        }
        return "";
    }
}