<?php
/**
 * User: hone
 * Date: 29/04/13
 * Time: 11:53 AM
 */

namespace app\telegram\observers;

use interfaces;

class imagecolumns  implements interfaces\observer {

    /**
     * The logic for how images are added to the configurable product
     * If there is a configurable product, should the configurable combine all
     * simple product images? And should some images be removed
     * from simple products such as the main image and only thumbnail kept?
     *
     * @param $configurable configurable
     * @return $configurable
     */
    public function run($configurable){
        /*
         *  look at app/magento/prep/configurable for logic hints
         *  look at createconfigurable.php for an example of how to use this observer
         */
        return $configurable;
    }

}