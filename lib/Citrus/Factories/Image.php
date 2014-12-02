<?php
/**
 */

class Citrus_Factories_Image{
    /**
     * @param $file
     * @return Varien_Image
     */
    public function build($file){
        return new Varien_Image($file);
    }
}