<?php
/**
 * User: hone
 * Date: 27/03/13
 * Time: 11:23 AM
 */

namespace lib;


class multijobs extends subject {

    /**
     *  @return string
     */
    public function run()
    {
        foreach($this->SplObjectStorage as $object){
            echo sprintf("\n\nRunning %s...\n\n", get_class($object));
            $object->run();
        }

        return "Done!";
    }

}