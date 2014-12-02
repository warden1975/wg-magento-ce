<?php
/**
 * User: hone
 * Date: 29/04/13
 * Time: 10:29 AM
 */

namespace lib;

use interfaces;

class callbacks implements interfaces\callbacks {

    /**
     * @var categories
     */
    protected $categories;

    /**
     * @var attributes
     */
    protected $attributes;

    /**
     * @var fromhtml
     */
    protected $fromhtml;

    /**
     * @param categories $categories
     * @param attributes $attributes
     * @param $fromhtml
     */
    public function __construct($categories, $attributes, $fromhtml){
        $this->categories = $categories;
        $this->attributes = $attributes;
        $this->fromhtml = $fromhtml;
    }

   /**
     * @param array $data
     * @return string
     */
    public function getCategoryIds($data)
    {
        return $this->categories->getDataById($data['id']);

    }

    /**
     * @param $data
     * @param $key
     * @return string
     */
    public function getAttribute($data, $key){
        return $this->attributes->getAttributesFromTags($data, $key);
    }

    /**
     * @param $data
     * @param $key
     * @return string
     */
    public function getAttributeFromHtml($data, $key){
        return $this->fromhtml->getAttribute($this->getDescription($data), "//ul/li", $key);
    }
}