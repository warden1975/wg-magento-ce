<?php
/**
 */

class Citrus_Factories_Domdocument {

    /**
     * @var DOMDocument
     */
    protected $DOMDocument;

    /**
     * @param DOMDocument $DOMDocument
     */
    public function __construct($DOMDocument){
        $this->DOMDocument = $DOMDocument;
    }

    /**
     * @param $xml_string
     * @return DOMDocument
     */
    public function build($xml_string){
        $DOMDocument = clone $this->DOMDocument;
        $DOMDocument->preserveWhiteSpace = false;
        $DOMDocument->formatOutput = true;
        $DOMDocument->loadXML($xml_string);

        return $DOMDocument;
    }

}