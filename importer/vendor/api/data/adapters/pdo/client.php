<?php

namespace api\data\adapters\pdo;

class client
{

    /**
     * @var \PDO
     */
    protected $PD0;

    /**
     * @var api\data\adapters\parseini\loader
     */
    protected $parseIniLoader;

    /**
     * @var api\data\adapters\pdo\query
     */
    protected $queryadapter;

	public function __construct($PDO, $parseIniLoader, $queryadapter){
		$this->PDO = $PDO;
		$this->parseIniLoader = $parseIniLoader;
		$this->queryadapter = $queryadapter;
	}

    public function setQueryadapter($queryadapter){
        $this->queryadapter = $queryadapter;
    }

	/**
	 * Factory Method
	 * @param $db_name string
	 * @return client
	 */
	public function selectDB($db_name){
		$this->PDO->query("USE $db_name;");
		return new self(
			$this->PDO,
			$this->parseIniLoader,
			$this->queryadapter
		);
	}

    /**
     * @param $collection_name
     * @param string $quote_character
     * @return get\collection
     */
    public function selectCollection($collection_name, $quote_character = '`'){
		$queries = $this->parseIniLoader->load($collection_name);
		return new $this->queryadapter(
			$this->PDO,
			$queries,
			$quote_character
		);
	}

}
