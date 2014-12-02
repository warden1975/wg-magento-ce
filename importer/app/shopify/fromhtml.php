<?php
/**
 * User: hone
 * Date: 27/03/13
 * Time: 9:44 AM
 */

namespace app\shopify;


class fromhtml {

    /**
     * @var domfactory
     */
    public $domfactory;

    /**
     * @param $domfactory
     */
    public function __construct($domfactory){
        $this->domfactory = $domfactory;
    }

    /**
     * @param string $html
     * @param string $key
     * @return string
     */
    public function getAttribute($html, $key){

        try{

            $xpath = $this->domfactory->newDOMXPathFromHtml($html);

            $nodes = $xpath->query("//ul/li");

            foreach($nodes as $node) {
                if( preg_match("!$key:!", $node->textContent) )
                    return strip_tags(trim(str_replace("$key:", "", $node->textContent)));
            }
        }
        catch ( Exception $e ){
            echo $e->getMessage();
        }
        return "";
    }
}