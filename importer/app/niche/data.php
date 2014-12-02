<?php

namespace app\niche;

use interfaces;

class data implements interfaces\data {

    /**
     * @var
     */
    protected $products;

    /**
     * @var
     */
    protected $styles;

    /**
     * @param $products \api\data\adapters\xml\loader
     * @param $styles \api\data\adapters\xml\loader
     */
    public function __construct($products, $styles)
    {
        $this->products = $products;
        $this->styles = $styles;
    }

    /**
     * @param $xmlObject
     * @param array $out
     * @return array
     */
    protected function xml2array ( $xmlObject, $out = array () )
    {
        foreach ( (array) $xmlObject as $index => $node ) {
            $out[$index] = ( is_object ( $node ) ) ? $this->xml2array ( $node ) : $node;
        }

        return $out;
    }

    /**
     * @param $vars
     * @return array
     */
    public function getRows($vars)
    {
        // arrays
        $styles = array();
        $products = array();
        $categories = array();

        $xml_styles = $this->styles->load();

        // add the styles
        foreach($xml_styles as $node) {

            $key = (string) $node->Code;

            $style = $this->xml2array($node);

            if (!in_array($style['Category'], $categories)) {
                $categories[] = $style['Category'];
            }

            $styles[$key] = $style;
        }

        // create array of products
        foreach($this->products->load() as $node) {

            $namekey = (string) $node->Code;
            $colour = (string) $node->Colour;
            $size = (string) $node->Size;
            $key = $namekey.$colour.$size;


            $products[$key] = $this->xml2array($node);

            // duplicated field in both entities
            unset($styles[$namekey]['EntityID']);

            $products[$key] = array_merge($products[$key], $styles[$namekey]);
        }

        // convert to 1 level array
        foreach($products as $id => $product) {

            foreach($product as $key => $attr) {
                if(!is_array($attr)) {
                    continue 1;
                }

                unset($products[$id][$key]);

                foreach($attr as $name => $val) {
                    $name = $key . '.' . $name;
                    $products[$id][$name] = $val;
                }
            }
        }

        return $products;
    }

}