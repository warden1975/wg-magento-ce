<?php
/**
 * User: hone
 * Date: 22/03/13
 * Time: 10:13 AM
 */

namespace app\shopify;


class callbacks {

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
    public function getDescription(array $data)
    {
        return trim(preg_replace('/\s+/', ' ', $data['body_html']));
    }

    /**
     * @param array $data
     * @return int
     */
    public function getIsInStock(array $data)
    {
        return (int) ($data['variants'][0]['inventory_quantity'] > 0);
    }

    /**
     * @param array $data
     * @return string
     */
    public function getCategoryIds(array $data)
    {
        return $this->categories->getData($data['id']);

    }

    /**
     * @param array $data
     * @return mixed
     */
    public function getImage(array $data)
    {
        return $data['images'][0]['src'];
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function getSmallImage(array $data)
    {
        return $data['images'][0]['src'];
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function getTumbnail(array $data)
    {
        return $data['images'][0]['src'];
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function getMediaGallery(array $data)
    {
        foreach($data['images'] as $image) {
            $images[] = $image['src'];
        }
        return implode(';', $images);
    }

    public function getAttribute(array $data, $key){
        return $this->attributes->getAttributesFromTags($data, $key);
    }

    public function getAttributeFromHtml(array $data, $key){
        return $this->fromhtml->getAttribute($this->getDescription($data), $key);
    }
}