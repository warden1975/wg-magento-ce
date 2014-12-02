<?php
/**
 */

class Citrus_Formatters_Array implements Citrus_Interfaces_Formatter {

    public function format($data){
        return (array)$data;
    }

}