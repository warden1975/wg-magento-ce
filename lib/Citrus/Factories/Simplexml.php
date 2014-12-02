<?php
/**
 */

class Citrus_Factories_Simplexml {

    protected $SimpleXMLElement;

    public function __construct($SimpleXMLElement = 'SimpleXMLElement') {
        $this->SimpleXMLElement = $SimpleXMLElement;
    }

    public function buildFromString($string){

        try {
            $xml = new $this->SimpleXMLElement($string);
            return $xml;
        }
        catch(Exception $e){
            return $e;
        }

    }

}