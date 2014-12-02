<?php

namespace api\data\adapters\xml;

class loader
{

	/**
	 *
	 * @var api_url
	 */
	public $api_url;

	protected $cache = array();

    /**
     * @param $api_url
     */
    public function __construct( $api_url )
    {
			$this->api_url = $api_url;
	}

    /**
     * @return \SimpleXMLElement
     * @throws \Exception
     */
    public function load()
    {

        if(isset($cache[$this->api_url])) {
            return $cache[$this->api_url];
        }

        $xml = \simplexml_load_file($this->api_url);

        if(!$xml) {
            throw new \Exception('Unable to load list from ' . $this->api_url);
        }

        $cache[$this->api_url] = $xml;

        return $xml;
	}
}