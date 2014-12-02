<?php

namespace api\data\adapters\pdo\get;
use api\data\adapters\pdo;
use api;

class collection extends \api\data\adapters\pdo\query {

    /**
     * @param $params array
     * @param int $fetchMode
     * @param string $statement
     * @return array|object
     */
    public function find($params, $fetchMode = \PDO::FETCH_OBJ, $statement = 'reusePrepared'){
		if($statement = $this->execute($params['query'], $params, $statement)){
			return $statement->fetchAll($fetchMode);
		}
	}


    /**
     * @param $params array
     * @param int $fetchMode
     * @param string $statement
     * @return array|object
     */
    public function findOne($params, $fetchMode = \PDO::FETCH_OBJ, $statement = 'reusePrepared'){
		if($statement = $this->execute($params['query'], $params, $statement)){

			return $statement->fetch($fetchMode);
		}
	}

    /**
     * @param $params array
     * @param int $fetchMode
     * @param string $statement
     * @return array|object
     */
    public function findIndex($params, $fetchMode = \PDO::FETCH_ASSOC, $statement = 'reusePrepared'){
		if($statement = $this->execute($params['query'], $params, $statement)){
			$results = $statement->fetchAll($fetchMode);
			if(sizeof($results)) {
				$keys = array_keys($results[0]);
				$finalresults = array();
				foreach($results as $result){
					$finalresults[$result[$keys[0]]] = $result[$keys[1]];
				}
				return $finalresults;
			}

		}
	}

}
