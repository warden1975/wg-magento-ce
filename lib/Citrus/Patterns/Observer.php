<?php
/**
 */

class Citrus_Patterns_Observer implements Citrus_Interfaces_Observer {

    public function update($subject){
        return get_class($this);
    }

}