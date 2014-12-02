<?php
/**
 * User: hone
 * Date: 29/04/13
 * Time: 11:35 AM
 */

namespace app\telegram;

use interfaces;

class configurableFactory implements interfaces\appfactory
{

    /**
     * @return configurable
     */
    public function build(){
        $configurable = new configurable(new \SplObjectStorage);
        $configurable->attach(new observers\arrange);
        $configurable->attach(new observers\createconfigurable);
        $configurable->attach(new observers\imagecolumns);
        $configurable->attach(new observers\configurableattributes);
        $configurable->attach(new observers\last);

        return $configurable;
    }

}