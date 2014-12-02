<?php

namespace api\data\adapters\parseini;

class loader
{

	/**
	 * @var basefile
	 */
	public $basefile;

    /**
     * @var array
     */
    protected $cache = array();

    /**
     * @param $basefile
     */
    public function __construct( $basefile )
    {
        $this->basefile = $basefile;
	}

    /**
     * @param object parse_ini_file $ini_file_relative_path
     * @return mixed
     */
    public function load($ini_file_relative_path)
    {
        //echo "\n\n$this->basefile/$ini_file_relative_path\n\n";

		if(!isset($this->cache[$ini_file_relative_path])) {
			$this->cache[$ini_file_relative_path] = \parse_ini_file("$this->basefile/$ini_file_relative_path", true);
		}

		return $this->cache[$ini_file_relative_path];
	}

}