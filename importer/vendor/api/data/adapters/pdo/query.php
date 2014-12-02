<?php

namespace api\data\adapters\pdo;
use api;

class query
{

    /**
     * @var \PDO
     */
    protected $PDO;
    /**
     * @var array
     */
    protected $queries;
    /**
     * @var string
     */
    protected $qc;

    /**
     * @var array
     */
    protected $prepared = array();

    /**
     * @var string
     */
    public $last_query = "";

    /**
     * @param $PDO
     * @param $queries
     * @param $quote_character
     */
    public function __construct($PDO, $queries, $quote_character)
    {
        $this->PDO = $PDO;
        $this->queries = $queries;
        $this->qc = $quote_character;
    }

    public function query($query_option, $params, $statement = 'reusePrepared')
    {
        return $this->execute($query_option, $params, $statement);
    }

    /**
     * @param $query
     * @param $params
     * @return string
     */
    public function getQuery($query, $params)
    {
        return $this->queries[$query];
    }

    /**
     * @param $query_option
     * @param $params
     * @param $statement
     * @return null
     * @throws \Exception
     */
    public function execute($query_option, $params, $statement)
    {

        $query = $this->getQuery($query_option, $params);

        if (!($statement = $this->$statement($query_option)))
            return null;

        unset($query['sql']);

        if (sizeof($query)) {

            foreach ($query as $param => $type) {
                if ($type === 'int')
                    $statement->bindValue($param, (int) $params[$param], \PDO::PARAM_INT);
                else
                    $statement->bindParam($param, $params[$param]);
            }

        }

        if (!$statement->execute()) {
            $error = $statement->errorInfo();
            throw new \Exception('Error: ' . $error[1] . ': ' . $error[2]);
        }

        $this->last_sql = $statement->queryString;

        /**
         * inserting the value for last insert id, if exists
         */
        $statement->last_insert_id = $this->PDO->lastInsertId();

        return $statement;
    }

    /**
     * @param $query
     * @return mixed
     */
    public function reusePrepared($query)
    {
        if (!isset($this->prepared[$query])) {
            if (isset($this->queries[$query]['sql'])) {

                $this->prepared[$query] = $this->PDO->prepare($this->queries[$query]['sql']);
                return $this->prepared[$query];
            } else {
                echo "$query could not be found";
            }
        } else {
            return $this->prepared[$query];
        }
    }


}