<?php
/**
 * User: hone
 * Date: 29/04/13
 * Time: 11:53 AM
 */

namespace app\niche\observers;

use interfaces;

class last  implements interfaces\observer {

    /**
     * Some final adjustments made to a simple/configurable product set
     * @param $configurable configurable
     * @return configurable
     */
    public function run($configurable){
        /*
         *  look at app/magento/prep/configurable for logic hints
         *  look at createconfigurable.php for an example of how to use this observer
         */
        return $configurable;
    }

}