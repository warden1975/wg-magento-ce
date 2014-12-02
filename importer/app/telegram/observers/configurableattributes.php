<?php
/**
 * User: hone
 * Date: 29/04/13
 * Time: 11:54 AM
 */

namespace app\telegram\observers;

use interfaces;

class configurableattributes implements interfaces\observer
{


    /**
     * Adds in configurable attributes.  This is determined by whether or not
     * the group of simple products has different colors, sizes etc
     * If a group of simple products only has different sizes then the configurable_attributes would be 'size'
     * If a group of simple products has different sizes and colours then the configurable attributes would be 'size,color'
     * look at app/magento/prep/configurable for logic hints from line 77
     * @param $configurable configurable
     * @return configurable
     */
    public function run($configurable)
    {
        $rows = $configurable->getData();

        foreach ($rows as $key => $config_prod) {
            $configurable_attributes = false;

            if ($config_prod['type'] == 'simple') {
                continue;
            }

            $sizes = array();
            $simple_keys = array();
            foreach ($rows as $skey => $simple_prod) {
                if ($simple_prod['type'] == 'configurable') {
                    continue;
                }

                if ($config_prod['namekey'] != $simple_prod['namekey']) {
                    continue;
                }

                if(!empty($simple_prod['color'])) {
                    $configurable_attributes[] = 'color';
                }

                if(isset($simple_prod['size'])) {
                    $sizes[$simple_prod['size']] = 1;
                }
                $simple_keys[] = $skey;
            }
            if(sizeof($sizes) > 1 ){
                $configurable_attributes[] = 'size';
            }
            else {
                if( sizeof($simple_keys) && sizeof( explode(".",$rows[$simple_keys[0]]['sku'] ) ) == 3){

                    foreach ($simple_keys as $skey) {
                        $simple_prod = $rows[$skey];
                        $new_sku = explode(".", $simple_prod['sku'] ); array_pop($new_sku);
                        $rows[$skey]['sku'] = implode(".", $new_sku );
                    }
                }
            }


            if($configurable_attributes) {
                $rows[$key]['configurable_attributes'] = implode(',', array_unique($configurable_attributes));
            }
        }

        $configurable->setData($rows);

        return $configurable;
    }
}