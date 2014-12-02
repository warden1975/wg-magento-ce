<?php
/**
 * User: hone
 * Date: 29/04/13
 * Time: 11:53 AM
 */

namespace app\telegram\observers;

use interfaces;

class createconfigurable implements interfaces\observer
{

    /**
     * @var array
     */
    protected $products = array();

    /**
     * If there is more than one simple product creates a configurable product
     * using a simple product as the basis for the configurable product and then
     * making a few changes to array values
     * @param $value array
     * @return bool
     */
    protected function createConfigurable($value)
    {
        if (count($value) < 1) {
            return false;
        }
        $configurable = $value;
        $configurable['name'] = $configurable['product_name'] = $this->getConfigurableName($value['namekey']);
        $configurable['sku'] = $value['namekey'];
        $configurable['has_options'] = 1;
        $configurable['qty'] = 0;
        $configurable['type'] = $configurable['product_type_id'] = 'configurable';

        // attrs that should be empty
        $empty_attrs = array('filemaker_sku', 'color', 'colour_filter', 'size', 'dsizes', 'ink_color', 'cover', 'source', 'ruling', 'style', 'use', 'point_width', 'suits', 'dpages', 'dstyle', 'dcover', 'dspecs', 'dcable', 'dquantity', 'dink', 'dtip', 'dmaterial', 'ddate', 'dcatalogue', 'dsuit', 'dformat', 'dzip', 'dfeature', 'dcontain', 'dhold', 'dlength', 'dheight', 'dnote', 'dextra', 'shelf_location', 'barcodes');
        foreach($empty_attrs as $attr) {
            if(isset($configurable[$attr]))
                $configurable[$attr] = '';
        }

        $configurable["use_config_manage_stock"] = $configurable["manage_stock"] = $configurable["is_in_stock"] = 0;
        $configurable["status"] = 1;
        // changing configurable to visible catalog/search
        $configurable["visibility"] = 4;

        return $configurable;
    }

    /**
     * @param string $namekey
     * @return string
     */
    protected function getConfigurableName($namekey)
    {
        $products_group = array();

        foreach ($this->products as $product) {
            if ($product['namekey'] != $namekey) {
                continue;
            }

            array_push($products_group, $product['name']);
        }

        $configurable_name = $products_group[0];

        foreach ($products_group as $simple_name) {
            $configurable_name = $this->compString($configurable_name, $simple_name);
        }

        return rtrim($configurable_name, ',');
    }

    /**
     * @param string $a
     * @param string $b
     * @return string
     */
    public function compString($a, $b)
    {
        $arr = explode(" ", $a);
        $brr = explode(" ", $b);
        $final = array();

        for ($i = 0; $i < count($arr); $i++) {
            if (isset($arr[$i]) && isset($brr[$i]) && ($arr[$i] == $brr[$i]))
                $final[] = $arr[$i];
            else
                break 1;
        }
        return trim(implode(" ", $final));
    }

    /**
     * method run adds a configurable product to a group of simple products
     * @param $configurable configurable
     * @return configurable
     */
    public function run($configurable)
    {
        $this->products = $rows = $configurable->getData();

        while (list($key, $simple_product) = each($rows)) {
            $configurable_product = $this->createConfigurable($simple_product);

            if(!$this->hasConfigurable($simple_product['namekey'])) {
                continue;
            }

            if (!$configurable_product) {
                continue;
            }

            if(isset($rows[$simple_product['namekey']]['price']) && ($rows[$simple_product['namekey']]['price'] > $simple_product['price'])) {
                $rows[$simple_product['namekey']]['price'] = $simple_product['price'];
            }

            if(isset($rows[$simple_product['namekey']]['special_price']) && ($rows[$simple_product['namekey']]['special_price'] > $simple_product['special_price'])) {
                $rows[$simple_product['namekey']]['special_price'] = $simple_product['special_price'];
            }

            if (isset($rows[$simple_product['namekey']])) {
                continue;
            }

            $rows[$simple_product['namekey']] = $configurable_product;
        }

        // Obtain a list of columns
        foreach ($rows as $key => $row) {
            $skus[$key]  = $row['sku'];
        }

        // Sort the data using the common key
        array_multisort($skus, SORT_DESC, SORT_STRING, $rows);

        $configurable->setData($rows);

        return $configurable;
    }

    /**
     * Defines if a simple product should or not have a configurable product
     *
     * @param $namekey
     * @return bool
     */
    public function hasConfigurable($namekey)
    {
        $num_namekeys = 0;

        foreach($this->products as $row) {
            if($namekey == $row['namekey']) {
                $num_namekeys++;
            }
        }

        return ($num_namekeys > 1) ? true: false;
    }

}