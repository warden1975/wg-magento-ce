<?php

class Citrus_Log {

    public function log($message, $level = null, $file = '', $forceLog = false){
        Mage::log($message, $level, $file, $forceLog);
    }

}