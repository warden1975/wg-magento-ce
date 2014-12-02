<?php
/**
 * User: hone
 * Date: 29/04/13
 * Time: 11:21 AM
 */

namespace app\telegram;

use lib;


class callbacks extends lib\callbacks
{
    /**
     * @param array $data
     * @return string
     */
    public function getDescription(array $data)
    {
        $description = trim(preg_replace('/\s+/', ' ', $data['description']));
        return $description;
    }

    /**
     * @param array $data
     * @return string
     */
    public function getShortDescription(array $data)
    {
        $description = trim(preg_replace('/\s+/', ' ', $data['description']));

        return substr($description, 0, (strpos($description, '.') + 1));
    }

    /**
     * @param array $data
     * @return string
     */
    public function getCategoryIds($data)
    {
        return $this->categories->getDataByName($data['type']);
    }

    /**
     * @param array $data
     * @return string
     */
    public function getDspecs(array $data)
    {
        return trim(preg_replace('/\s+/', ' ', $data['specs']));
    }

//    /**
//     * If QTY = 0 then enable the "BACK SOON" label.
//     * If QTY > 0 then disable the "BACK SOON" label.
//     *
//     * @param array $data
//     * @return string
//     */
//    public function getLabels(array $data)
//    {
//        return ($data['available_to_sell'] == 0) ? 'Back Soon' : '';
//    }

    /**
     * @param array $data
     * @return string
     */
    public function getSku(array $data)
    {
        $sku = trim($data['namekey']);

        if(!empty($data['colour']))
            $sku .= '.' . trim($data['colour']);

        if(!empty($data['size']))
            $sku .= '.' . trim($data['size']);

        $sku = strtolower($sku);

        return $sku;
    }

    /**
     * Changes on the requests, it's always coming through active
     * IF Current = 1 then enable the product in Magento.
     * IF Current = 2 then disable the product in Magento.
     *
     * @param array $data
     * @return string
     */
    public function getStatus(array $data)
    {
        return 1;
//        return ($data['current'] == 1) ? '1' : '2';
    }

    /**
     * should return base or telegram or base,telegram
     * @param array $data
     */
    public function getBase(array $data){
        if($data['store'] == "admin")
            return "base,telegram";
        elseif($data['store'] == "admin,notemaker")
            return "base";
        elseif ($data['store'] == "admin,telegram")
            return "telegram";
    }

    /**
     * Special price should be blank if zero
     * @param array $data
     * @return string
     */
    public function getSpecialPrice(array $data){
        if($data['special_price'] == "0" || $data['special_price'] == 0) {
            return NULL;
        }

        return $data['special_price'];
    }
}