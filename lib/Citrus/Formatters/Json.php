<?php
/**
 */

class Citrus_Formatters_Json implements Citrus_Interfaces_Formatter {

    public function format($data){
        return json_encode($data);
    }
    
}