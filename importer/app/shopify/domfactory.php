<?php
/**
 * User: hone
 * Date: 27/03/13
 * Time: 9:44 AM
 */

namespace app\shopify;


class domfactory {

    /**
     * @var string
     */
    public $DomDocument;

    /**
     * @var string
     */
    public $DOMXPath;

    /**
     * @param string $DomDocument
     * @param string $DOMXPath
     */
    public function __construct($DomDocument = 'DomDocument', $DOMXPath = 'DOMXPath') {
        $this->DomDocument = $DomDocument;
        $this->DOMXPath = $DOMXPath;
    }

    /**
     * @param $html
     * @return \DomDocument
     */
    public function newDomDocument($html){
        $dom = new $this->DomDocument;
        $dom->loadHTML($html);
        return $dom;
    }

    /**
     * @param $html
     * @return \DOMXPath
     */
    public function newDOMXPathFromHtml($html){
        $dom = $this->newDomDocument($html);
        return new $this->DOMXPath($dom);
    }
}